<?php

// ══════════════════════════════════════════════════════════════════
// Handle action form submissions — exact same logic as inspiration
// ══════════════════════════════════════════════════════════════════
if (Input::checkInput('request', 'post', '1')) {

    $serieId = (int) sanAsID(Input::get('serie-id', 'post'));

    if ($serieId > 0) {
        if (Input::checkInput('PinTop', 'post', '0')) {
            StoriSerieController::pinOnTop($serieId);
        }
        if (Input::checkInput('Published', 'post', '0')) {
            StoriSerieController::changeState('Published', $serieId);
        }
        if (Input::checkInput('Pending', 'post', '0')) {
            StoriSerieController::changeState('Pending', $serieId);
        }
        if (Input::checkInput('Deleted', 'post', '0')) {
            StoriSerieController::changeState('Deleted', $serieId);
        }
    }

    Redirect::to("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
}

// ── Sort handling ──
if (empty($_SESSION['thisserie_sort'])) {
    $_SESSION['thisserie_sort'] = '`pin_top` DESC, `ID` DESC';
    Redirect::to("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
}

$sortMap = [
    'sort-id'           => '`ID`',
    'sort-views'        => '`views`',
    'sort-pin_top'      => '`pin_top`',
    'sort-Package'      => '`package`',
    'sort-created_date' => '`created_date`',
    'sort-state'        => '`state`',
];
foreach ($sortMap as $key => $col) {
    if (Input::checkInput($key, 'post', '0')) {
        $cur = $_SESSION['thisserie_sort'];
        $_SESSION['thisserie_sort'] = (strpos($cur, $col) !== false && strpos($cur, 'ASC') !== false)
            ? "$col DESC" : "$col ASC";
        Redirect::to("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
    }
}

// ── Search / Filter ──
$search_sql    = '';
$filter_state  = Input::get('filter_state', 'get') ?: '';
$sql_val_array = [];

if ($filter_state && $filter_state !== 'all') {
    $search_sql .= ' AND `state` = ?';
    $sql_val_array[] = $filter_state;
} else {
    $search_sql .= ' AND `state` != ?';
    $sql_val_array[] = 'Deleted';
}

$keyword = '';
if (Input::checkInput('search', 'get', '1') && Input::checkInput('keyword', 'get', '1')) {
    $keyword    = urldecode(Input::get('keyword', 'get'));
    $search_sql .= " AND (`serie_title` LIKE ? OR `company_name` LIKE ? OR `serie_description` LIKE ? OR `package_type` LIKE ? OR `state` LIKE ? OR `ID` = ?)";
    $kw = "%{$keyword}%";
    array_push($sql_val_array, $kw, $kw, $kw, $kw, $kw, (int)$keyword);
}

// ── Count query ──
$storiSerieTable = new StoriSerie();
$storiSerieTable->selectQuery(
    "SELECT * FROM `stori_serie` WHERE 1 $search_sql ORDER BY {$_SESSION['thisserie_sort']}",
    $sql_val_array
);

// ── Pagination ──
$rowsLimit    = 20;
$pageNumber   = Input::checkInput('page', 'get', '1') ? max(0, (int)sanAsID(Input::get('page', 'get'))) : 0;
$totalStore   = $storiSerieTable->count();
$totalPages   = (int)ceil($totalStore / $rowsLimit);
$offsetNumber = $pageNumber * $rowsLimit;
if ($offsetNumber >= $totalStore) { $pageNumber = 0; $offsetNumber = 0; }

// ── Data query ──
$storiSerieTable = new StoriSerie();
$storiSerieTable->selectQuery(
    "SELECT * FROM `stori_serie` WHERE 1 $search_sql ORDER BY {$_SESSION['thisserie_sort']} LIMIT {$offsetNumber},{$rowsLimit}",
    $sql_val_array
);

// ── Stats ──
$statsTable = new StoriSerie();
$statsTable->selectQuery("SELECT state, COUNT(*) as cnt FROM `stori_serie` WHERE `state` != 'Deleted' GROUP BY state");
$statsByState = ['Published' => 0, 'Pending' => 0];
if ($statsTable->count()) {
    foreach ($statsTable->data() as $row) {
        $statsByState[$row->state] = $row->cnt;
    }
}
$totalActive = array_sum($statsByState);

$viewsTable = new StoriSerie();
$viewsTable->selectQuery("SELECT SUM(views) as total FROM `stori_serie` WHERE `state` != 'Deleted'");
$totalViews = $viewsTable->count() ? ($viewsTable->first()->total ?? 0) : 0;
?>



<!-- ── Stats ── -->
 <div class="row row-card-content">
    <div class="col-sm-12">
      <!-- Numbers -->
        <div class="sj-stats-row">
          <a href="?filter_state=all" class="sj-stat-card">
            <div class="sj-stat-icon all"><i class="fa fa-list"></i></div>
            <div>
              <div class="sj-stat-num"><?= $totalActive ?></div>
              <div class="sj-stat-label">Total des offres</div>
            </div>
          </a>
          <a href="?filter_state=Published" class="sj-stat-card">
            <div class="sj-stat-icon published"><i class="fa fa-check-circle"></i></div>
            <div>
              <div class="sj-stat-num"><?= $statsByState['Published'] ?? 0 ?></div>
              <div class="sj-stat-label">Publiées</div>
            </div>
          </a>
          <a href="?filter_state=Pending" class="sj-stat-card">
            <div class="sj-stat-icon pending"><i class="fa fa-clock"></i></div>
            <div>
              <div class="sj-stat-num"><?= $statsByState['Pending'] ?? 0 ?></div>
              <div class="sj-stat-label">En attente</div>
            </div>
          </a>
          <a href="?filter_state=all" class="sj-stat-card">
            <div class="sj-stat-icon views"><i class="fa fa-eye"></i></div>
            <div>
              <div class="sj-stat-num"><?= number_format($totalViews) ?></div>
              <div class="sj-stat-label">Vues totales</div>
            </div>
          </a>
        </div>

        <!-- ── Toolbar ── -->
        <div class="sj-toolbar">
          <form method="get" action="" style="display:contents">
            <div class="sj-search-box">
              <i class="fa fa-search"></i>
              <input type="text" name="keyword"
                    value="<?= htmlspecialchars($keyword) ?>"
                    placeholder="Rechercher titre, entreprise…">
              <button type="submit" name="search" value="1">Chercher</button>
            </div>
            <?php if ($keyword): ?>
              <a href="?" class="sj-filter-tab">
                <i class="fa fa-times"></i> Effacer
              </a>
            <?php endif; ?>
          </form>

          <div class="sj-filter-tabs">
            <?php
            $cur_filter = Input::get('filter_state', 'get') ?: '';
            $filterOpts = [
              ''          => ['label' => 'Tout',       'class' => ''],
              'Published' => ['label' => 'Publiées',   'class' => 'pub'],
              'Pending'   => ['label' => 'En attente', 'class' => 'pend'],
            ];
            foreach ($filterOpts as $val => $opt):
              $active = ($cur_filter === $val) ? 'active' : '';
              $qs     = $val ? "?filter_state={$val}" : '?filter_state=all';
            ?>
              <a href="<?= $qs ?>"
                class="sj-filter-tab <?= $opt['class'] ?> <?= $active ?>">
                <?= $opt['label'] ?>
              </a>
            <?php endforeach; ?>
          </div>

          <a href="<?= DNADMIN ?>/app/emploi/new"
            class="btn btn-sm btn-danger" style="margin-left:auto">
            <i class="fa fa-plus"></i> Nouvelle offre
          </a>
        </div>

        <!-- ── Table card ── -->
        <div class="sj-table-card">
          <div class="sj-table-head">
            <div class="sj-table-title">
              <i class="fa fa-briefcase"></i>
              Liste des offres
              <span class="sj-result-count">
                <?= $totalStore ?> résultat<?= $totalStore > 1 ? 's' : '' ?>
              </span>
            </div>
          </div>

          <div style="overflow-x:auto">
            <!-- Sort form -->
            <form method="post" id="sort-form">
              <table class="sj-table">
                <thead>
                  <tr>
                    <th><button type="submit" name="sort-id"># <i class="fa fa-sort"></i></button></th>
                    <th>Offre</th>
                    <th class="hide-xs"><button type="submit" name="sort-Package">Emplacement <i class="fa fa-sort"></i></button></th>
                    <th class="hide-xs">Visibilité</th>
                    <th class="hide-xs"><button type="submit" name="sort-created_date">Date <i class="fa fa-sort"></i></button></th>
                    <th class="hide-xs"><button type="submit" name="sort-views">Vues <i class="fa fa-sort"></i></button></th>
                    <th class="hide-xs"><button type="submit" name="sort-pin_top">Pin <i class="fa fa-sort"></i></button></th>
                    <th><button type="submit" name="sort-state">État <i class="fa fa-sort"></i></button></th>
                    <th style="width:90px">Actions</th>
                  </tr>
                </thead>
                <tbody>

                <?php if ($storiSerieTable->count()): ?>
                  <?php foreach ($storiSerieTable->data() as $row): ?>
                  <?php
                    $rid        = (int) $row->ID;
                    $stateClass = match($row->state) {
                      'Published' => 'sj-badge-published',
                      'Pending'   => 'sj-badge-pending',
                      default     => 'sj-badge-deleted',
                    };
                    $comps = [];
                    for ($ci = 1; $ci <= 7; $ci++) {
                        $ck = "comp_{$ci}";
                        if (!empty($row->$ck)) $comps[] = $row->$ck;
                    }
                    $exps = [];
                    for ($ei = 1; $ei <= 4; $ei++) {
                        $ek = "exp_{$ei}";
                        if (!empty($row->$ek)) $exps[] = $row->$ek;
                    }
                  ?>

                  <!-- ── Table row ── -->
                  <tr>
                    <td style="color:var(--muted);font-size:12px;font-weight:600"><?= $rid ?></td>

                    <td>
                      <div class="sj-job-identity">
                        <?php if (!empty($row->photo)): ?>
                          <img src="<?= DNADMIN . '/' . htmlspecialchars($row->photo) ?>"
                              class="sj-logo" alt="Logo"
                              data-toggle="modal"
                              data-target="#sjModal<?= $rid ?>">
                        <?php else: ?>
                          <div class="sj-logo-placeholder">
                            <i class="fa fa-building"></i>
                          </div>
                        <?php endif; ?>
                        <div>
                          <div class="sj-job-title"
                              data-toggle="modal"
                              data-target="#sjModal<?= $rid ?>">
                            <?= htmlspecialchars($row->serie_title) ?>
                          </div>
                          <div class="sj-job-company">
                            <i class="fa fa-building" style="font-size:10px"></i>
                            <?= htmlspecialchars($row->company_name ?? '—') ?>
                            <?php if (!empty($row->job_type)): ?>
                              &bull;
                              <span class="sj-badge sj-badge-type">
                                <?= htmlspecialchars($row->job_type) ?>
                              </span>
                            <?php endif; ?>
                          </div>
                        </div>
                      </div>
                    </td>

                    <td class="hide-xs">
                      <span style="font-size:12px;color:var(--muted)">
                        <?= htmlspecialchars($row->package ?? '—') ?>
                      </span>
                    </td>

                    <td class="hide-xs">
                      <?php if ($row->package_type === 'Public'): ?>
                        <span class="sj-badge sj-badge-public">
                          <i class="fa fa-globe"></i> Publique
                        </span>
                      <?php elseif ($row->package_type === 'Private'): ?>
                        <span class="sj-badge sj-badge-private">
                          <i class="fa fa-lock"></i> Privée
                        </span>
                      <?php else: ?>
                        <span style="color:var(--muted);font-size:12px">
                          <?= htmlspecialchars($row->package_type ?? '—') ?>
                        </span>
                      <?php endif; ?>
                    </td>

                    <td class="hide-xs">
                      <span style="font-size:12px">
                        <?= !empty($row->created_date) ? date('d/m/Y', strtotime($row->created_date)) : '—' ?>
                      </span>
                    </td>

                    <td class="hide-xs">
                      <span style="font-size:13px;font-weight:600">
                        <?= number_format($row->views) ?>
                      </span>
                    </td>

                    <td class="hide-xs">
                      <?php if ($row->pin_top > 0): ?>
                        <div class="sj-pin">
                          <i class="fa fa-thumbtack"></i> <?= $row->pin_top ?>
                        </div>
                      <?php else: ?>
                        <span style="color:#ddd">—</span>
                      <?php endif; ?>
                    </td>

                    <td>
                      <span class="sj-badge <?= $stateClass ?>">
                        <?= htmlspecialchars($row->state) ?>
                      </span>
                    </td>

                    <td>
                      <a data-toggle="modal" data-target="#sjModal<?= $rid ?>"
                        class="btn btn-sm btn-default small-btn"
                        title="Voir détails"
                        style="margin-right:3px">
                        <i class="fa fa-eye"></i>
                      </a>
                      <a href="<?= DNADMIN ?>/app/emploi/edit/<?= $rid ?>"
                        class="btn btn-sm btn-default small-btn"
                        title="Modifier">
                        <i class="fa fa-pencil"></i>
                      </a>
                    </td>
                  </tr>

                  <!-- ══════════════════════════════════════════════════════
                      BOOTSTRAP MODAL — button name = action (same as
                      the inspiration project logic)
                      ══════════════════════════════════════════════════════ -->
                  <div class="modal fade sj-modal"
                      id="sjModal<?= $rid ?>"
                      tabindex="-1"
                      role="dialog"
                      aria-labelledby="sjModalLabel<?= $rid ?>">
                    <div class="modal-dialog" role="document" style="max-width:520px;width:90%">
                      <div class="modal-content">

                        <!-- Header -->
                        <div class="modal-header">
                          <button type="button" class="close"
                                  data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                          <h4 class="modal-title" id="sjModalLabel<?= $rid ?>">
                            <?php if (!empty($row->photo)): ?>
                              <img src="<?= DNADMIN . '/' . htmlspecialchars($row->photo) ?>"
                                  style="width:26px;height:26px;border-radius:5px;object-fit:contain;background:#fff;padding:2px;margin-right:7px;vertical-align:middle"
                                  alt="">
                            <?php endif; ?>
                            <?= htmlspecialchars($row->serie_title) ?>
                          </h4>
                        </div>

                        <!-- Body -->
                        <div class="modal-body">

                          <?php if (!empty($row->photo)): ?>
                            <div style="text-align:center;margin-bottom:16px">
                              <img src="<?= DNADMIN . '/' . htmlspecialchars($row->photo) ?>"
                                  style="max-height:140px;border-radius:8px;object-fit:contain;border:1px solid #eee;padding:6px"
                                  class="img img-responsive" alt="Logo">
                            </div>
                          <?php endif; ?>

                          <div class="sj-modal-grid">
                            <div class="sj-modal-field">
                              <label>Entreprise</label>
                              <div class="val"><?= htmlspecialchars($row->company_name ?? '—') ?></div>
                            </div>
                            <div class="sj-modal-field">
                              <label>Type d'emploi</label>
                              <div class="val"><?= htmlspecialchars($row->job_type ?? '—') ?></div>
                            </div>
                            <div class="sj-modal-field">
                              <label>Emplacement</label>
                              <div class="val"><?= htmlspecialchars($row->package ?? '—') ?></div>
                            </div>
                            <div class="sj-modal-field">
                              <label>Visibilité</label>
                              <div class="val"><?= htmlspecialchars($row->package_type ?? '—') ?></div>
                            </div>
                            <div class="sj-modal-field">
                              <label>Vues</label>
                              <div class="val"><?= number_format($row->views) ?></div>
                            </div>
                            <div class="sj-modal-field">
                              <label>Créé le</label>
                              <div class="val">
                                <?= !empty($row->created_date) ? date('d/m/Y', strtotime($row->created_date)) : '—' ?>
                              </div>
                            </div>
                          </div>

                          <?php if (!empty($row->education)): ?>
                            <div class="sj-modal-field" style="margin-bottom:12px">
                              <label>Éducation requise</label>
                              <div class="val"><?= htmlspecialchars($row->education) ?></div>
                            </div>
                          <?php endif; ?>

                          <?php if (!empty($comps)): ?>
                            <div class="sj-modal-field" style="margin-bottom:12px">
                              <label>Compétences</label>
                              <div style="margin-top:5px">
                                <?php foreach ($comps as $c): ?>
                                  <span class="sj-skill-tag"
                                        style="background:#e0e7ff;color:#3730a3">
                                    <?= htmlspecialchars($c) ?>
                                  </span>
                                <?php endforeach; ?>
                              </div>
                            </div>
                          <?php endif; ?>

                          <?php if (!empty($exps)): ?>
                            <div class="sj-modal-field" style="margin-bottom:12px">
                              <label>Expériences</label>
                              <div style="margin-top:5px">
                                <?php foreach ($exps as $ex): ?>
                                  <span class="sj-skill-tag"
                                        style="background:#fef3c7;color:#92400e">
                                    <?= htmlspecialchars($ex) ?>
                                  </span>
                                <?php endforeach; ?>
                              </div>
                            </div>
                          <?php endif; ?>

                          <?php if (!empty($row->serie_description)): ?>
                            <div class="sj-modal-field"
                                style="padding-top:12px;border-top:1px solid #eee">
                              <label>Description</label>
                              <div class="val"
                                  style="font-size:13px;line-height:1.6;margin-top:4px;white-space:pre-wrap">
                                <?= htmlspecialchars($row->serie_description) ?>
                              </div>
                            </div>
                          <?php endif; ?>

                        </div><!-- /.modal-body -->

                        <!-- Footer — button name IS the action, exactly like inspiration project -->
                        <div class="modal-footer">
                          <form method="post" style="display:inline">
                            <input type="hidden" name="request"  value="serie-status">
                            <input type="hidden" name="serie-id" value="<?= $rid ?>">

                            <button type="submit" name="PinTop"
                                    class="btn btn-default btn-sm">
                              <i class="fa fa-thumbtack"></i> Épingler
                            </button>

                            <?php if ($row->state !== 'Published'): ?>
                              <button type="submit" name="Published"
                                      class="btn btn-success btn-sm">
                                <i class="fa fa-check"></i> Publier
                              </button>
                            <?php endif; ?>

                            <?php if ($row->state !== 'Pending'): ?>
                              <button type="submit" name="Pending"
                                      class="btn btn-warning btn-sm">
                                <i class="fa fa-clock-o"></i> En attente
                              </button>
                            <?php endif; ?>

                            <button type="submit" name="Deleted"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Supprimer cette offre ? Cette action est irréversible.')">
                              <i class="fa fa-trash"></i> Supprimer
                            </button>
                          </form>

                          <a href="<?= DNADMIN ?>/app/emploi/edit/<?= $rid ?>"
                            class="btn btn-default btn-sm">
                            <i class="fa fa-pencil"></i> Modifier
                          </a>

                          <button type="button"
                                  class="btn btn-default btn-sm pull-right"
                                  data-dismiss="modal">
                            <i class="fa fa-times"></i> Fermer
                          </button>
                        </div><!-- /.modal-footer -->

                      </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                  </div><!-- /.modal -->

                  <?php endforeach; ?>

                <?php else: ?>
                  <tr>
                    <td colspan="9">
                      <div class="sj-empty">
                        <div class="icon"><i class="fa fa-briefcase"></i></div>
                        <h3>Aucune offre trouvée</h3>
                        <p>
                          <?= $keyword
                            ? 'Aucun résultat pour "' . htmlspecialchars($keyword) . '".'
                            : "Commencez par ajouter une nouvelle offre." ?>
                        </p>
                      </div>
                    </td>
                  </tr>
                <?php endif; ?>

                </tbody>
              </table>
            </form>
          </div>

          <!-- ── Pagination ── -->
          <?php if ($totalPages > 1): ?>
          <div class="sj-pagination">
            <span>
              <?= $offsetNumber + 1 ?>–<?= min($offsetNumber + $rowsLimit, $totalStore) ?>
              sur <?= $totalStore ?>
            </span>
            <div class="sj-page-links">
              <?php
              $qs_base = ($keyword ? "?search=1&keyword=" . urlencode($keyword) : '?')
                      . ($filter_state ? "&filter_state={$filter_state}" : '');
              $qs_sep  = ($keyword || $filter_state) ? '&' : '?';
              for ($p = 0; $p < $totalPages; $p++):
                $active = $p === $pageNumber ? 'active' : '';
                $link   = $qs_base . ($p > 0 ? "{$qs_sep}page={$p}" : '');
              ?>
                <a href="<?= $link ?>" class="sj-page-link <?= $active ?>">
                  <?= $p + 1 ?>
                </a>
              <?php endfor; ?>
            </div>
          </div>
          <?php endif; ?>

        </div><!-- /.sj-table-card -->
    </div>
 </div>




