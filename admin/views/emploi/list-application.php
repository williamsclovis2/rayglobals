<?php
/**
 * applications-list.php
 *
 * CRITICAL FIX: The sort <form> previously wrapped the entire table,
 * making all status/delete forms NESTED inside it. Nested forms are
 * illegal HTML — browsers silently ignore inner forms. All action
 * forms now live OUTSIDE the table using hidden inputs + JS submit.
 */

/* ── Sort init ── */
if (empty($_SESSION['app_sort'])) {
    $_SESSION['app_sort'] = '`ID` DESC';
    Redirect::to("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
}

/* ── Sort toggle ── */
$sortMap = [
    'sort-id'     => '`ID`',
    'sort-status' => '`status`',
    'sort-date'   => '`created_date`',
    'sort-job'    => '`job_title`',
    'sort-name'   => '`lastname`',
];
foreach ($sortMap as $key => $col) {
    if (Input::checkInput($key, 'post', '0')) {
        $cur = $_SESSION['app_sort'];
        $_SESSION['app_sort'] = (strpos($cur,$col)!==false && strpos($cur,'ASC')!==false)
            ? "$col DESC" : "$col ASC";
        Redirect::to("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
    }
}

/* ── Action handling ── */
if (Input::checkInput('request', 'post', '1')) {
    $appId = (int)Input::get('app-id', 'post');
    $req   = Input::get('request', 'post');

    /* DEBUG — log every POST action so we can trace exactly what arrives */
    $_dbgLine = date('Y-m-d H:i:s')
        . ' | req='      . $req
        . ' | app-id='   . $appId
        . ' | pending='  . ($_POST['pending']  ?? 'n/a')
        . ' | accepted=' . ($_POST['accepted'] ?? 'n/a')
        . ' | rejected=' . ($_POST['rejected'] ?? 'n/a')
        . ' | reviewing='. ($_POST['reviewing'] ?? 'n/a')
        . PHP_EOL;
    @file_put_contents(dirname(__FILE__) . '/app_action_debug.log', $_dbgLine, FILE_APPEND);

    if ($req === 'application-status' && $appId > 0) {
        /*
         * FIX: The JS sets ONE status to '1' and the rest to '0'.
         * ALL fields are always present in POST, so checkInput() (existence
         * check) always matches 'pending' first. We must check value === '1'.
         */
        $_statusSet = false;
        foreach (['pending', 'reviewing', 'accepted', 'rejected'] as $s) {
            if (Input::get($s, 'post') === '1') {
                $_res = JobApplicationController::changeStatus($s, $appId);
                if (isset($_res->ERRORS) && $_res->ERRORS === false) {
                    Session::put('success', 'Statut mis à jour : ' . $s);
                } else {
                    $_em = isset($_res->ERRORS_SCRIPT) ? implode(' ', (array)$_res->ERRORS_SCRIPT) : 'Erreur inconnue';
                    Session::put('errors', 'Échec statut : ' . $_em);
                }
                $_statusSet = true;
                break;
            }
        }
        if (!$_statusSet) {
            /* Nothing had value=1 — dump POST so we can diagnose */
            Session::put('errors', 'DEBUG: aucun statut=1 reçu. POST dump: ' . json_encode($_POST));
        }
    }

    if ($req === 'application-delete' && $appId > 0) {
        $_res = JobApplicationController::delete($appId);
        if (isset($_res->ERRORS) && $_res->ERRORS === false) {
            Session::put('success', 'Candidature supprimée.');
        } else {
            $_em = isset($_res->ERRORS_SCRIPT) ? implode(' ', (array)$_res->ERRORS_SCRIPT) : 'Erreur inconnue';
            Session::put('errors', 'Échec suppression : ' . $_em);
        }
    }

    Redirect::to("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
}

/* ── Filters ── */
$filter_status = Input::get('filter_status', 'get') ?: '';
$keyword       = '';
$filters       = [];
if ($filter_status && $filter_status !== 'all') $filters['status'] = $filter_status;
if (Input::checkInput('search','get','1') && Input::checkInput('keyword','get','1')) {
    $keyword = urldecode(Input::get('keyword','get'));
    $filters['keyword'] = $keyword;
}

/* ── Pagination ── */
$rowsLimit  = 20;
$pageNumber = Input::checkInput('page','get','1') ? max(0,(int)Input::get('page','get')) : 0;
$totalStore = JobApplicationController::countAll($filters);
$totalPages = (int)ceil($totalStore / $rowsLimit);
$offsetNum  = $pageNumber * $rowsLimit;
if ($offsetNum >= $totalStore && $totalStore > 0) { $pageNumber = 0; $offsetNum = 0; }

/* ── Data & stats ── */
$appTable = JobApplicationController::getAll($filters, $_SESSION['app_sort'], $offsetNum, $rowsLimit);
$stats    = JobApplicationController::getStats();

$SL = [
    'pending'   => ['label'=>'En attente', 'cls'=>'sj-badge-pending',   'ico'=>'fa-clock'],
    'reviewing' => ['label'=>'En cours',   'cls'=>'sj-badge-type',      'ico'=>'fa-search'],
    'accepted'  => ['label'=>'Approuvée',  'cls'=>'sj-badge-published', 'ico'=>'fa-check'],
    'rejected'  => ['label'=>'Rejetée',    'cls'=>'sj-badge-deleted',   'ico'=>'fa-times'],
];
?>

<style>
.app-actions { position:relative; display:inline-block; }
.app-dot-btn {
    background:none; border:1px solid #e2e8f0; border-radius:6px;
    width:30px; height:30px; cursor:pointer; font-size:17px; font-weight:700;
    color:#64748b; display:flex; align-items:center; justify-content:center;
    line-height:1; transition:background .15s;
}
.app-dot-btn:hover { background:#f1f5f9; border-color:#cbd5e1; }
.app-drop {
    display:none; position:absolute; right:0; top:34px; z-index:9999;
    background:#fff; border:1px solid #e2e8f0; border-radius:10px;
    box-shadow:0 8px 24px rgba(0,0,0,.13); min-width:195px; overflow:hidden;
}
.app-actions.open .app-drop { display:block; }
.adrop-btn {
    display:flex; align-items:center; gap:9px;
    padding:9px 14px; font-size:13px; color:#334155;
    cursor:pointer; border:none; background:none; width:100%; text-align:left;
    transition:background .1s;
}
.adrop-btn:hover { background:#f8fafc; }
.adrop-btn.green { color:#16a34a; }
.adrop-btn.red   { color:#dc2626; }
.adrop-btn.orange{ color:#d97706; }
.adrop-hr { border:none; border-top:1px solid #f1f5f9; margin:3px 0; }
</style>

<?php if (!empty($_SESSION['success'])): ?>
  <div class="alert alert-success" style="margin-bottom:16px">
    <i class="fa fa-check-circle"></i>
    <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
  </div>
<?php endif; ?>
<?php if (!empty($_SESSION['errors'])): ?>
  <div class="alert alert-danger" style="margin-bottom:16px">
    <i class="fa fa-exclamation-circle"></i>
    <?= htmlspecialchars($_SESSION['errors']); unset($_SESSION['errors']); ?>
  </div>
<?php endif; ?>

<!--
  SORT FORM — lives outside the table, submitted by JS.
  Action forms also live outside the table (one shared form below).
  This prevents illegal nested forms.
-->
<form method="post" id="sort-form" style="display:none">
  <input type="hidden" name="sort-id"     id="si-sort-id">
  <input type="hidden" name="sort-name"   id="si-sort-name">
  <input type="hidden" name="sort-job"    id="si-sort-job">
  <input type="hidden" name="sort-date"   id="si-sort-date">
  <input type="hidden" name="sort-status" id="si-sort-status">
</form>

<!-- Shared action form — populated by JS before submit -->
<form method="post" id="action-form" style="display:none">
  <input type="hidden" name="request" id="af-request">
  <input type="hidden" name="app-id"  id="af-app-id">
  <input type="hidden" name="pending"  id="af-pending"  value="0">
  <input type="hidden" name="accepted" id="af-accepted" value="0">
  <input type="hidden" name="rejected" id="af-rejected" value="0">
</form>

<div class="row row-card-content">
<div class="col-sm-12">

  <!-- Stats -->
  <div class="sj-stats-row">
    <a href="?filter_status=all" class="sj-stat-card">
      <div class="sj-stat-icon all"><i class="fa fa-inbox"></i></div>
      <div><div class="sj-stat-num"><?= $stats->total ?></div><div class="sj-stat-label">Total</div></div>
    </a>
    <a href="?filter_status=pending" class="sj-stat-card">
      <div class="sj-stat-icon pending"><i class="fa fa-clock"></i></div>
      <div><div class="sj-stat-num"><?= $stats->pending ?></div><div class="sj-stat-label">En attente</div></div>
    </a>
    <a href="?filter_status=accepted" class="sj-stat-card">
      <div class="sj-stat-icon published"><i class="fa fa-check-circle"></i></div>
      <div><div class="sj-stat-num"><?= $stats->accepted ?></div><div class="sj-stat-label">Approuvées</div></div>
    </a>
    <a href="?filter_status=rejected" class="sj-stat-card">
      <div class="sj-stat-icon" style="background:#fee2e2;color:#dc2626"><i class="fa fa-times-circle"></i></div>
      <div><div class="sj-stat-num"><?= $stats->rejected ?></div><div class="sj-stat-label">Rejetées</div></div>
    </a>
  </div>

  <!-- Toolbar -->
  <div class="sj-toolbar">
    <form method="get" action="">
      <div class="sj-search-box">
        <i class="fa fa-search"></i>
        <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>" placeholder="Nom, e-mail, poste…">
        <button type="submit" name="search" value="1">Chercher</button>
      </div>
      <?php if ($keyword): ?>
        <a href="?" class="sj-filter-tab"><i class="fa fa-times"></i> Effacer</a>
      <?php endif; ?>
    </form>
    <div class="sj-filter-tabs">
      <?php
      $tabs = [''=>'Toutes','pending'=>'En attente','accepted'=>'Approuvées','rejected'=>'Rejetées'];
      foreach ($tabs as $val => $label):
        $active = ($filter_status===$val || ($val===''&&$filter_status==='all')) ? 'active' : '';
        $qs = $val ? "?filter_status={$val}" : '?filter_status=all';
      ?>
        <a href="<?= $qs ?>" class="sj-filter-tab <?= $active ?>"><?= $label ?></a>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Table -->
  <div class="sj-table-card">
    <div class="sj-table-head">
      <div class="sj-table-title">
        <i class="fa fa-users"></i> Candidatures
        <span class="sj-result-count"><?= $totalStore ?> résultat<?= $totalStore>1?'s':'' ?></span>
      </div>
    </div>

    <div style="overflow-x:auto">
      <table class="sj-table">
        <thead>
          <tr>
            <th><button type="button" class="sj-sort-btn" onclick="doSort('sort-id')"># <i class="fa fa-sort"></i></button></th>
            <th><button type="button" class="sj-sort-btn" onclick="doSort('sort-name')">Candidat <i class="fa fa-sort"></i></button></th>
            <th class="hide-xs"><button type="button" class="sj-sort-btn" onclick="doSort('sort-job')">Poste <i class="fa fa-sort"></i></button></th>
            <th class="hide-xs">Contact</th>
            <th class="hide-xs">Documents</th>
            <th class="hide-xs"><button type="button" class="sj-sort-btn" onclick="doSort('sort-date')">Date <i class="fa fa-sort"></i></button></th>
            <th><button type="button" class="sj-sort-btn" onclick="doSort('sort-status')">Statut <i class="fa fa-sort"></i></button></th>
            <th style="width:46px"></th>
          </tr>
        </thead>
        <tbody>

        <?php if ($appTable->count()): ?>
          <?php foreach ($appTable->data() as $row):
            $aid      = (int)$row->ID;
            $sl       = $SL[$row->status] ?? ['label'=>$row->status,'cls'=>'','ico'=>'fa-circle'];
            $diplomas = !empty($row->file_diplomas) ? json_decode($row->file_diplomas, true) : [];
            if (!is_array($diplomas)) $diplomas = [];
            $hasCV    = !empty($row->file_cv);
            $hasCover = !empty($row->file_cover);
            $hasDips  = !empty($diplomas);
            $st       = $row->status;
          ?>

          <tr>
            <td style="color:var(--muted);font-size:12px;font-weight:600"><?= $aid ?></td>

            <td>
              <div class="sj-job-identity">
                <div class="sj-logo-placeholder" style="background:#e0e7ff;color:#1e3a5f;font-weight:700">
                  <?= strtoupper(substr($row->firstname??'A',0,1)) ?>
                </div>
                <div>
                  <div class="sj-job-title" style="cursor:pointer"
                       data-toggle="modal" data-target="#appModal<?= $aid ?>">
                    <?= htmlspecialchars($row->firstname.' '.$row->lastname) ?>
                  </div>
                  <div class="sj-job-company">
                    <?= htmlspecialchars($row->city??'') ?>
                    <?php if(!empty($row->country)): ?> &bull; <?= htmlspecialchars($row->country) ?><?php endif; ?>
                  </div>
                </div>
              </div>
            </td>

            <td class="hide-xs">
              <div style="font-size:13px;font-weight:600;color:#1a1a2e"><?= htmlspecialchars($row->job_title) ?></div>
              <div style="font-size:12px;color:var(--muted)"><i class="fa fa-building" style="font-size:10px"></i> <?= htmlspecialchars($row->company_name) ?></div>
            </td>

            <td class="hide-xs">
              <div style="font-size:12px"><?= htmlspecialchars($row->email) ?></div>
              <div style="font-size:12px;color:var(--muted)"><?= htmlspecialchars($row->phone) ?></div>
            </td>

            <td class="hide-xs">
              <div style="display:flex;flex-wrap:wrap;gap:4px">
                <?php if ($hasCV): ?>
                  <a href="<?= DNADMIN.'/'.htmlspecialchars($row->file_cv) ?>" target="_blank" class="sj-badge sj-badge-type" title="Ouvrir CV"><i class="fa fa-external-link-alt"></i> CV</a>
                <?php endif; ?>
                <?php if ($hasCover): ?>
                  <a href="<?= DNADMIN.'/'.htmlspecialchars($row->file_cover) ?>" target="_blank" class="sj-badge sj-badge-public" title="Ouvrir LM"><i class="fa fa-external-link-alt"></i> LM</a>
                <?php endif; ?>
                <?php foreach($diplomas as $di=>$dp): ?>
                  <a href="<?= DNADMIN.'/'.htmlspecialchars($dp) ?>" target="_blank" class="sj-badge" style="background:#fef3c7;color:#92400e"><i class="fa fa-external-link-alt"></i> Dip.<?= $di+1 ?></a>
                <?php endforeach; ?>
                <?php if(!$hasCV&&!$hasCover&&!$hasDips): ?><span style="color:var(--muted);font-size:11px">—</span><?php endif; ?>
              </div>
            </td>

            <td class="hide-xs">
              <span style="font-size:12px"><?= !empty($row->created_date)?date('d/m/Y',strtotime($row->created_date)):'—' ?></span>
            </td>

            <td>
              <span class="sj-badge <?= $sl['cls'] ?>">
                <i class="fa <?= $sl['ico'] ?>"></i> <?= $sl['label'] ?>
              </span>
            </td>

            <!-- 3-dot dropdown — uses JS to submit the shared action-form -->
            <td>
              <div class="app-actions" id="aa-<?= $aid ?>">
                <button type="button" class="app-dot-btn" onclick="toggleDrop(<?= $aid ?>)">&#8942;</button>
                <div class="app-drop">

                  <button type="button" class="adrop-btn"
                          data-toggle="modal" data-target="#appModal<?= $aid ?>"
                          onclick="closeDrop(<?= $aid ?>)">
                    <i class="fa fa-eye" style="width:14px;text-align:center"></i> Voir détails
                  </button>

                  <div class="adrop-hr"></div>

                  <?php if ($st === 'pending'): ?>
                    <button type="button" class="adrop-btn green"
                            onclick="doStatus(<?= $aid ?>,'accepted')">
                      <i class="fa fa-check" style="width:14px;text-align:center"></i> Approuver
                    </button>
                    <button type="button" class="adrop-btn red"
                            onclick="doStatus(<?= $aid ?>,'rejected',true)">
                      <i class="fa fa-times" style="width:14px;text-align:center"></i> Rejeter
                    </button>

                  <?php elseif ($st === 'accepted'): ?>
                    <span class="adrop-btn" style="color:#16a34a;cursor:default;opacity:.7">
                      <i class="fa fa-check-circle" style="width:14px;text-align:center"></i> Approuvée
                    </span>
                    <button type="button" class="adrop-btn red"
                            onclick="doStatus(<?= $aid ?>,'rejected',true)">
                      <i class="fa fa-times" style="width:14px;text-align:center"></i> Rejeter
                    </button>
                    <button type="button" class="adrop-btn orange"
                            onclick="doStatus(<?= $aid ?>,'pending',true)">
                      <i class="fa fa-undo" style="width:14px;text-align:center"></i> Remettre en attente
                    </button>

                  <?php elseif ($st === 'rejected'): ?>
                    <span class="adrop-btn" style="color:#dc2626;cursor:default;opacity:.7">
                      <i class="fa fa-times-circle" style="width:14px;text-align:center"></i> Rejetée
                    </span>
                    <button type="button" class="adrop-btn green"
                            onclick="doStatus(<?= $aid ?>,'accepted')">
                      <i class="fa fa-check" style="width:14px;text-align:center"></i> Approuver
                    </button>
                    <button type="button" class="adrop-btn orange"
                            onclick="doStatus(<?= $aid ?>,'pending',true)">
                      <i class="fa fa-undo" style="width:14px;text-align:center"></i> Remettre en attente
                    </button>
                  <?php endif; ?>

                  <div class="adrop-hr"></div>

                  <button type="button" class="adrop-btn red"
                          onclick="doDelete(<?= $aid ?>)">
                    <i class="fa fa-trash" style="width:14px;text-align:center"></i> Supprimer
                  </button>

                </div>
              </div>
            </td>
          </tr>

          <!-- DETAIL MODAL -->
          <div class="modal fade sj-modal" id="appModal<?= $aid ?>" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document" style="max-width:580px;width:92%">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                  <h4 class="modal-title">
                    <i class="fa fa-user" style="margin-right:7px;color:#e63946"></i>
                    <?= htmlspecialchars($row->firstname.' '.$row->lastname) ?>
                    <small style="font-size:13px;color:var(--muted);font-weight:400;margin-left:8px">— <?= htmlspecialchars($row->job_title) ?></small>
                  </h4>
                </div>
                <div class="modal-body">

                  <div style="margin-bottom:16px;display:flex;align-items:center;gap:12px;flex-wrap:wrap">
                    <span class="sj-badge <?= $sl['cls'] ?>" style="font-size:12px;padding:5px 12px">
                      <i class="fa <?= $sl['ico'] ?>"></i> <?= $sl['label'] ?>
                    </span>
                    <span style="font-size:12px;color:var(--muted)">
                      Reçue le <?= !empty($row->created_date)?date('d/m/Y',strtotime($row->created_date)):'—' ?>
                    </span>
                  </div>

                  <div class="sj-modal-grid">
                    <div class="sj-modal-field"><label>Prénom &amp; Nom</label><div class="val"><?= htmlspecialchars($row->firstname.' '.$row->lastname) ?></div></div>
                    <div class="sj-modal-field"><label>Genre</label><div class="val"><?= htmlspecialchars($row->gender??'—') ?></div></div>
                    <div class="sj-modal-field"><label>Naissance</label><div class="val"><?= !empty($row->dob)?date('d/m/Y',strtotime($row->dob)):'—' ?></div></div>
                    <div class="sj-modal-field"><label>E-mail</label><div class="val"><a href="mailto:<?= htmlspecialchars($row->email) ?>"><?= htmlspecialchars($row->email) ?></a></div></div>
                    <div class="sj-modal-field"><label>Téléphone</label><div class="val"><?= htmlspecialchars($row->phone) ?></div></div>
                    <div class="sj-modal-field"><label>Pays / Ville</label><div class="val"><?= htmlspecialchars(($row->country??'').' — '.($row->city??'')) ?></div></div>
                    <div class="sj-modal-field"><label>Études</label><div class="val"><?= htmlspecialchars($row->education??'—') ?></div></div>
                    <div class="sj-modal-field"><label>Expérience</label><div class="val"><?= htmlspecialchars($row->experience??'—') ?></div></div>
                    <?php if(!empty($row->current_position)): ?>
                    <div class="sj-modal-field"><label>Poste actuel</label><div class="val"><?= htmlspecialchars($row->current_position) ?></div></div>
                    <?php endif; ?>
                    <?php if(!empty($row->linkedin)): ?>
                    <div class="sj-modal-field"><label>LinkedIn</label><div class="val"><a href="<?= htmlspecialchars($row->linkedin) ?>" target="_blank"><?= htmlspecialchars($row->linkedin) ?></a></div></div>
                    <?php endif; ?>
                  </div>

                  <!-- Documents -->
                  <div style="margin-top:16px;padding-top:12px;border-top:1px solid #eee">
                    <div style="font-size:11px;font-weight:700;letter-spacing:.5px;color:var(--muted);text-transform:uppercase;margin-bottom:10px">Documents</div>
                    <div style="display:flex;flex-wrap:wrap;gap:8px">
                      <?php if ($hasCV): ?>
                        <a href="<?= DNADMIN.'/'.htmlspecialchars($row->file_cv) ?>" download target="_blank" class="btn btn-default btn-sm" style="display:inline-flex;align-items:center;gap:6px">
                          <i class="fa fa-file-pdf" style="color:#e63946"></i> CV <i class="fa fa-download" style="font-size:10px;opacity:.6"></i>
                        </a>
                      <?php endif; ?>
                      <?php if ($hasCover): ?>
                        <a href="<?= DNADMIN.'/'.htmlspecialchars($row->file_cover) ?>" download target="_blank" class="btn btn-default btn-sm" style="display:inline-flex;align-items:center;gap:6px">
                          <i class="fa fa-file-word" style="color:#1e3a5f"></i> Lettre <i class="fa fa-download" style="font-size:10px;opacity:.6"></i>
                        </a>
                      <?php endif; ?>
                      <?php foreach($diplomas as $di=>$dp): ?>
                        <a href="<?= DNADMIN.'/'.htmlspecialchars($dp) ?>" download target="_blank" class="btn btn-default btn-sm" style="display:inline-flex;align-items:center;gap:6px">
                          <i class="fa fa-graduation-cap" style="color:#92400e"></i> Diplôme <?= $di+1 ?> <i class="fa fa-download" style="font-size:10px;opacity:.6"></i>
                        </a>
                      <?php endforeach; ?>
                      <?php if(!$hasCV&&!$hasCover&&!$hasDips): ?><span style="color:var(--muted);font-size:13px">— Aucun document joint —</span><?php endif; ?>
                    </div>
                  </div>

                  <?php if(!empty($row->message)): ?>
                  <div style="margin-top:14px;padding-top:12px;border-top:1px solid #eee">
                    <div style="font-size:11px;font-weight:700;letter-spacing:.5px;color:var(--muted);text-transform:uppercase;margin-bottom:8px">Message</div>
                    <div style="font-size:13px;line-height:1.7;white-space:pre-wrap;color:#1a1a2e;background:#f8fafc;border-radius:8px;padding:12px">
                      <?= htmlspecialchars($row->message) ?>
                    </div>
                  </div>
                  <?php endif; ?>

                </div>
                <div class="modal-footer" style="display:flex;align-items:center;flex-wrap:wrap;gap:8px">
                  <?php if ($st === 'pending'): ?>
                    <button type="button" class="btn btn-success btn-sm" onclick="doStatus(<?= $aid ?>,'accepted')"><i class="fa fa-check"></i> Approuver</button>
                    <button type="button" class="btn btn-danger btn-sm"  onclick="doStatus(<?= $aid ?>,'rejected',true)"><i class="fa fa-times"></i> Rejeter</button>
                  <?php elseif ($st === 'accepted'): ?>
                    <span style="font-size:13px;color:#16a34a;font-weight:600"><i class="fa fa-check-circle"></i> Approuvée</span>
                    <button type="button" class="btn btn-danger btn-sm"   onclick="doStatus(<?= $aid ?>,'rejected',true)"><i class="fa fa-times"></i> Rejeter</button>
                    <button type="button" class="btn btn-warning btn-sm"  onclick="doStatus(<?= $aid ?>,'pending',true)"><i class="fa fa-undo"></i> Remettre en attente</button>
                  <?php elseif ($st === 'rejected'): ?>
                    <span style="font-size:13px;color:#dc2626;font-weight:600"><i class="fa fa-times-circle"></i> Rejetée</span>
                    <button type="button" class="btn btn-success btn-sm"  onclick="doStatus(<?= $aid ?>,'accepted')"><i class="fa fa-check"></i> Approuver</button>
                    <button type="button" class="btn btn-warning btn-sm"  onclick="doStatus(<?= $aid ?>,'pending',true)"><i class="fa fa-undo"></i> Remettre en attente</button>
                  <?php endif; ?>
                  <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" style="margin-left:auto"><i class="fa fa-times"></i> Fermer</button>
                </div>
              </div>
            </div>
          </div><!-- /.modal -->

          <?php endforeach; ?>

        <?php else: ?>
          <tr>
            <td colspan="8">
              <div class="sj-empty">
                <div class="icon"><i class="fa fa-inbox"></i></div>
                <h3>Aucune candidature trouvée</h3>
                <p><?= $keyword ? 'Aucun résultat pour "'.htmlspecialchars($keyword).'".' : 'Les candidatures soumises via le site apparaîtront ici.' ?></p>
              </div>
            </td>
          </tr>
        <?php endif; ?>

        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <div class="sj-pagination">
      <span><?= $offsetNum+1 ?>–<?= min($offsetNum+$rowsLimit,$totalStore) ?> sur <?= $totalStore ?></span>
      <div class="sj-page-links">
        <?php
        $qb  = ($keyword ? "?search=1&keyword=".urlencode($keyword) : '?').($filter_status?"&filter_status={$filter_status}":'');
        $sep = ($keyword||$filter_status)?'&':'?';
        for ($p=0;$p<$totalPages;$p++):
          $a=$p===$pageNumber?'active':'';
          $l=$qb.($p>0?"{$sep}page={$p}":'');
        ?>
          <a href="<?= $l ?>" class="sj-page-link <?= $a ?>"><?= $p+1 ?></a>
        <?php endfor; ?>
      </div>
    </div>
    <?php endif; ?>

  </div>
</div>
</div>

<script>
/* ── Dropdown ── */
document.addEventListener('click', function(e) {
    if (!e.target.closest('.app-actions')) {
        document.querySelectorAll('.app-actions.open').forEach(function(el){ el.classList.remove('open'); });
    }
});
function toggleDrop(id) {
    var el = document.getElementById('aa-' + id);
    var was = el.classList.contains('open');
    document.querySelectorAll('.app-actions.open').forEach(function(x){ x.classList.remove('open'); });
    if (!was) el.classList.add('open');
}
function closeDrop(id) {
    var el = document.getElementById('aa-' + id);
    if (el) el.classList.remove('open');
}

/* ── Sort ── */
function doSort(field) {
    document.getElementById('si-' + field).value = '1';
    document.getElementById('sort-form').submit();
}

/* ── Status change via shared action-form ── */
function doStatus(id, status, confirm_first) {
    if (confirm_first && !confirm('Confirmer ce changement de statut ?')) return;
    closeDrop(id);
    var f = document.getElementById('action-form');
    document.getElementById('af-request').value  = 'application-status';
    document.getElementById('af-app-id').value   = id;
    document.getElementById('af-pending').value  = status === 'pending'  ? '1' : '0';
    document.getElementById('af-accepted').value = status === 'accepted' ? '1' : '0';
    document.getElementById('af-rejected').value = status === 'rejected' ? '1' : '0';
    f.submit();
}

/* ── Delete ── */
function doDelete(id) {
    if (!confirm('Supprimer cette candidature ? Action irréversible.')) return;
    closeDrop(id);
    var f = document.getElementById('action-form');
    document.getElementById('af-request').value  = 'application-delete';
    document.getElementById('af-app-id').value   = id;
    document.getElementById('af-pending').value  = '0';
    document.getElementById('af-accepted').value = '0';
    document.getElementById('af-rejected').value = '0';
    f.submit();
}
</script>