<?php
/* ─────────────────────────────────────────────────────
   Build competences & experiences arrays from DB data
───────────────────────────────────────────────────── */
$d = $stori_serie_data;

$competences = [];
for ($i = 1; $i <= 7; $i++) {
    $key = "comp_{$i}";
    $val = isset($d->$key) ? trim($d->$key) : '';
    if ($val !== '') $competences[] = htmlspecialchars($val);
}
if (empty($competences)) $competences = [''];

$experiences = [];
for ($i = 1; $i <= 4; $i++) {
    $key = "exp_{$i}";
    $val = isset($d->$key) ? trim($d->$key) : '';
    if ($val !== '') $experiences[] = htmlspecialchars($val);
}
if (empty($experiences)) $experiences = [''];

/* ── shared option sets (must match create form & controller) ── */
$jobTypes = [
    'tempsplein'    => 'Temps plein',
    'tempspartiel'  => 'Temps partiel',
    'roster'        => 'Roster / Consultant',
    'stage'         => 'Stage',
    'interim'       => 'Intérim',
    'apprentissage' => 'Apprentissage',
];

$categories = [
    'informatique' => 'Informatique / Tech',
    'finance'      => 'Finance / Comptabilité',
    'marketing'    => 'Marketing / Communication',
    'sante'        => 'Santé',
    'education'    => 'Éducation',
    'Chauffeur'    => 'Chauffeur',
    'construction' => 'Construction / BTP',
    'agriculture'  => 'Agriculture',
    'autre'        => 'Autre',
];

$expRanges = [
    '1-2' => '1 – 2 ans',
    '2-3' => '2 – 3 ans',
    '3-6' => '3 – 6 ans',
    '6+'  => 'Plus de 6 ans',
];

$langs = [
    'FRANCAIS' => 'Français',
    'ENGLISH'  => 'English',
    'SWAHILI'  => 'Swahili',
];
?>

<div class="container-fluid" style="padding: 28px 20px 80px; max-width: 1140px;">

  <!-- ── Steps bar ── -->
  <div class="row">
    <div class="col-md-12">
      <div class="vf-steps-card">
        <div class="vf-steps-header">
          <div class="vf-steps-header-left">
            <p>Progression</p>
            <h3>Modifier l'offre #<?= (int)$d->ID ?></h3>
          </div>
          <div class="vf-progress-inline">
            <div class="vf-progress-track">
              <div class="vf-progress-fill" id="vf-progress-fill"></div>
            </div>
            <span class="vf-progress-pct" id="vf-progress-pct">25%</span>
          </div>
        </div>

        <div class="vf-steps-list">
          <?php
          $steps = [
              1 => ['label' => 'Poste',        'sub' => 'Titre, entreprise, logo'],
              2 => ['label' => 'Compétences',  'sub' => 'Skills & expériences'],
              3 => ['label' => 'Descriptions', 'sub' => 'Accroche & détails'],
              4 => ['label' => 'Paramètres',   'sub' => 'Visibilité & publication'],
          ];
          foreach ($steps as $num => $info): ?>
          <div class="vf-step <?= $num === 1 ? 'active' : '' ?>" data-step="<?= $num ?>" onclick="vfGoTo(<?= $num ?>)">
            <div class="vf-step-num"><?= $num ?></div>
            <div class="vf-step-info">
              <div class="vf-step-label"><?= $info['label'] ?></div>
              <div class="vf-step-sub"><?= $info['sub'] ?></div>
            </div>
            <i class="fa fa-check vf-step-check"></i>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- ── Form ── -->
  <div class="row">
    <div class="col-md-8">

      <?php if (!empty($_SESSION['success'])): ?>
        <div class="vf-alert vf-alert-success" style="margin-bottom:16px">
          <i class="fa fa-check-circle"></i>
          <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
        </div>
      <?php endif; ?>

      <?php if ($form->ERRORS && (
          @$form->ERRORS_SCRIPT['email'][0]           == 'NOTUNIQUE' ||
          @$form->ERRORS_SCRIPT['document_number'][0] == 'NOTUNIQUE'
      )): ?>
        <div class="vf-alert vf-alert-error">
          <i class="fa fa-exclamation-circle"></i>
          <?php include 'email_used' . PL; ?>
        </div>
      <?php endif; ?>

      <form method="post" id="vf-edit-form" enctype="multipart/form-data">

        <input type="hidden" name="request" value="emploi-edit">
        <input type="hidden" name="id"                    value="<?= (int)$d->ID ?>">
        <input type="hidden" name="register_submited"     value="1">
        <input type="hidden" name="register-event_token"  value="de220168957bd2ccff08f88e9939b95f">

        <!-- ══════════════════════════════════════
             STEP 1 — Poste
        ══════════════════════════════════════ -->
        <div class="vf-card active" id="vf-step-1">
          <div class="vf-card-header">
            <div class="vf-card-header-icon"><i class="fa fa-briefcase"></i></div>
            <div>
              <h2>Informations sur le poste</h2>
              <p>Titre, entreprise, catégorie, localisation</p>
            </div>
          </div>
          <div class="vf-card-body">

            <!-- Row 1: Titre + Entreprise -->
            <div class="vf-field-row">
              <div class="vf-field-group">
                <label class="vf-label" for="serie_title">
                  Titre du poste <span class="req">*</span>
                  <?php if ($form->ERRORS && @$form->ERRORS_SCRIPT['serie_title'][0]): ?>
                    <span class="vf-err"><?= htmlspecialchars($form->ERRORS_SCRIPT['serie_title'][0]) ?></span>
                  <?php endif; ?>
                </label>
                <input type="text" class="vf-input" name="register-serie_title" id="serie_title"
                       placeholder="Ex: Développeur Full-Stack Senior"
                       value="<?= htmlspecialchars($d->serie_title ?? '') ?>"
                       maxlength="150" required>
              </div>
              <div class="vf-field-group">
                <label class="vf-label" for="company_name">
                  Nom de l'entreprise <span class="req">*</span>
                  <?php if ($form->ERRORS && @$form->ERRORS_SCRIPT['company_name'][0]): ?>
                    <span class="vf-err"><?= htmlspecialchars($form->ERRORS_SCRIPT['company_name'][0]) ?></span>
                  <?php endif; ?>
                </label>
                <input type="text" class="vf-input" name="register-company_name" id="company_name"
                       placeholder="Ex: Kigali Tech Ltd"
                       value="<?= htmlspecialchars($d->company_name ?? '') ?>"
                       maxlength="150" required>
              </div>
            </div>

            <!-- Row 2: Type d'emploi + Langue -->
            <div class="vf-field-row">
              <div class="vf-field-group">
                <label class="vf-label" for="job_type">
                  Type d'emploi <span class="req">*</span>
                  <?php if ($form->ERRORS && @$form->ERRORS_SCRIPT['job_type'][0]): ?>
                    <span class="vf-err"><?= htmlspecialchars($form->ERRORS_SCRIPT['job_type'][0]) ?></span>
                  <?php endif; ?>
                </label>
                <select class="vf-select" name="register-job_type" id="job_type" required>
                  <option value="">— Sélectionnez —</option>
                  <?php foreach ($jobTypes as $val => $label): ?>
                    <option value="<?= $val ?>" <?= ($d->job_type ?? '') === $val ? 'selected' : '' ?>>
                      <?= htmlspecialchars($label) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="vf-field-group">
                <label class="vf-label" for="language">Langue de l'annonce</label>
                <select class="vf-select" name="register-language" id="language">
                  <?php foreach ($langs as $val => $label): ?>
                    <option value="<?= $val ?>" <?= ($d->language ?? '') === $val ? 'selected' : '' ?>>
                      <?= htmlspecialchars($label) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <!-- Row 3: Catégorie + Localisation ← NEW -->
            <div class="vf-field-row">
              <div class="vf-field-group">
                <label class="vf-label" for="category">
                  Catégorie <span class="req">*</span>
                  <?php if ($form->ERRORS && @$form->ERRORS_SCRIPT['category'][0]): ?>
                    <span class="vf-err"><?= htmlspecialchars($form->ERRORS_SCRIPT['category'][0]) ?></span>
                  <?php endif; ?>
                </label>
                <select class="vf-select" name="register-category" id="category" required>
                  <option value="">— Sélectionnez —</option>
                  <?php foreach ($categories as $val => $label): ?>
                    <option value="<?= $val ?>" <?= ($d->category ?? '') === $val ? 'selected' : '' ?>>
                      <?= htmlspecialchars($label) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="vf-field-group">
                <label class="vf-label" for="location">
                  Localisation <span class="req">*</span>
                  <?php if ($form->ERRORS && @$form->ERRORS_SCRIPT['location'][0]): ?>
                    <span class="vf-err"><?= htmlspecialchars($form->ERRORS_SCRIPT['location'][0]) ?></span>
                  <?php endif; ?>
                </label>
                <input type="text" class="vf-input" name="register-location" id="location"
                       placeholder="Ex: Kigali, Gasabo"
                       value="<?= htmlspecialchars($d->location ?? '') ?>"
                       maxlength="150" required>
              </div>
            </div>

            <!-- Logo -->
            <div class="vf-field-group" style="margin-top:8px">
              <label class="vf-label">
                Logo de l'entreprise
                <?php if ($form->ERRORS && @$form->ERRORS_SCRIPT['featuredImage'][0]): ?>
                  <span class="vf-err"><?= htmlspecialchars($form->ERRORS_SCRIPT['featuredImage'][0]) ?></span>
                <?php endif; ?>
              </label>
              <?php if (!empty($d->photo)): ?>
                <div class="vf-existing-photo">
                  <img src="<?= DNADMIN . '/' . htmlspecialchars($d->photo) ?>" alt="Logo actuel">
                  <span>Logo actuel — téléversez une nouvelle image pour remplacer</span>
                </div>
              <?php endif; ?>
              <div class="vf-upload-zone" id="vf-upload-zone">
                <input type="file" name="featuredImage" id="featuredImage"
                       accept="image/png,image/jpeg,image/gif">
                <div class="icon"><i class="fa fa-cloud-upload-alt"></i></div>
                <div class="upload-text">Glissez une image ici, ou <strong>cliquez pour parcourir</strong></div>
                <div class="upload-hint">PNG, JPG, GIF · Max 5 Mo</div>
              </div>
              <div class="vf-upload-preview" id="vf-upload-preview">
                <img id="vf-preview-img" src="" alt="Prévisualisation">
              </div>
            </div>

          </div>
          <div class="vf-form-actions">
            <a href="<?= DNADMIN ?>/app/serie/list" class="vf-btn vf-btn-outline">
              <i class="fa fa-times vf-btn-icon"></i> Annuler
            </a>
            <button type="button" class="vf-btn vf-btn-primary" onclick="vfNext(1)">
              Suivant <i class="fa fa-arrow-right vf-btn-icon"></i>
            </button>
          </div>
        </div>

        <!-- ══════════════════════════════════════
             STEP 2 — Compétences & Expériences
        ══════════════════════════════════════ -->
        <div class="vf-card" id="vf-step-2">
          <div class="vf-card-header">
            <div class="vf-card-header-icon"><i class="fa fa-star"></i></div>
            <div>
              <h2>Compétences &amp; Expériences</h2>
              <p>Ajoutez, supprimez ou réordonnez librement</p>
            </div>
          </div>
          <div class="vf-card-body">

            <!-- Competences -->
            <div class="vf-divider"><span>Compétences requises</span></div>
            <div class="vf-dynamic-list" id="vf-comp-list">
              <?php foreach (array_values($competences) as $idx => $val): ?>
              <div class="vf-dynamic-item" draggable="true">
                <span class="vf-drag-handle"><i class="fa fa-grip-vertical"></i></span>
                <span class="vf-item-num"><?= $idx + 1 ?></span>
                <input type="text" name="register-competences[]"
                       placeholder="Ex: Maîtrise de JavaScript"
                       value="<?= htmlspecialchars($val) ?>" maxlength="250">
                <button type="button" class="vf-btn-remove" onclick="vfRemove(this,'vf-comp-list')">
                  <i class="fa fa-times"></i>
                </button>
              </div>
              <?php endforeach; ?>
            </div>
            <button type="button" class="vf-btn-add"
                    onclick="vfAdd('vf-comp-list','register-competences[]','Compétence')">
              <i class="fa fa-plus"></i> Ajouter une compétence
            </button>
            <div class="vf-list-note">
              <i class="fa fa-info-circle"></i> Maximum 7 compétences. Au moins 1 requise.
            </div>

            <!-- Education -->
            <div class="vf-divider" style="margin-top:28px"><span>Éducation</span></div>
            <div class="vf-field-group">
              <label class="vf-label" for="education">
                Niveau d'éducation requis <span class="req">*</span>
                <?php if ($form->ERRORS && @$form->ERRORS_SCRIPT['education'][0]): ?>
                  <span class="vf-err"><?= htmlspecialchars($form->ERRORS_SCRIPT['education'][0]) ?></span>
                <?php endif; ?>
              </label>
              <input type="text" class="vf-input" name="register-education" id="education"
                     placeholder="Ex: Licence en Informatique ou équivalent"
                     value="<?= htmlspecialchars($d->education ?? '') ?>"
                     maxlength="250" required>
            </div>

            <!-- Experience range ← NEW -->
            <div class="vf-divider" style="margin-top:10px"><span>Années d'expérience requises</span></div>
            <div class="vf-field-row">
              <div class="vf-field-group">
                <label class="vf-label" for="experience_range">
                  Tranche d'expérience
                  <small style="color:#888;font-weight:400">(utilisée pour le filtre du site)</small>
                </label>
                <select class="vf-select" name="register-experience_range" id="experience_range">
                  <option value="">— Sélectionnez —</option>
                  <?php foreach ($expRanges as $val => $label): ?>
                    <option value="<?= $val ?>" <?= ($d->experience_range ?? '') === $val ? 'selected' : '' ?>>
                      <?= htmlspecialchars($label) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <!-- Experiences dynamic list -->
            <div class="vf-divider" style="margin-top:10px"><span>Détails d'expériences requises</span></div>
            <div class="vf-dynamic-list" id="vf-exp-list">
              <?php foreach (array_values($experiences) as $idx => $val): ?>
              <div class="vf-dynamic-item" draggable="true">
                <span class="vf-drag-handle"><i class="fa fa-grip-vertical"></i></span>
                <span class="vf-item-num"><?= $idx + 1 ?></span>
                <input type="text" name="register-experiences[]"
                       placeholder="Ex: 3 ans d'expérience en développement web"
                       value="<?= htmlspecialchars($val) ?>" maxlength="250">
                <button type="button" class="vf-btn-remove" onclick="vfRemove(this,'vf-exp-list')">
                  <i class="fa fa-times"></i>
                </button>
              </div>
              <?php endforeach; ?>
            </div>
            <button type="button" class="vf-btn-add"
                    onclick="vfAdd('vf-exp-list','register-experiences[]','Expérience')">
              <i class="fa fa-plus"></i> Ajouter une expérience
            </button>
            <div class="vf-list-note">
              <i class="fa fa-info-circle"></i> Maximum 4 expériences. Au moins 1 requise.
            </div>

          </div>
          <div class="vf-form-actions">
            <button type="button" class="vf-btn vf-btn-outline" onclick="vfPrev(2)">
              <i class="fa fa-arrow-left vf-btn-icon"></i> Précédent
            </button>
            <button type="button" class="vf-btn vf-btn-primary" onclick="vfNext(2)">
              Suivant <i class="fa fa-arrow-right vf-btn-icon"></i>
            </button>
          </div>
        </div>

        <!-- ══════════════════════════════════════
             STEP 3 — Descriptions
        ══════════════════════════════════════ -->
        <div class="vf-card" id="vf-step-3">
          <div class="vf-card-header">
            <div class="vf-card-header-icon"><i class="fa fa-align-left"></i></div>
            <div>
              <h2>Descriptions</h2>
              <p>Courte accroche et détails complets du poste</p>
            </div>
          </div>
          <div class="vf-card-body">

            <div class="vf-field-group" style="margin-bottom:16px">
              <label class="vf-label" for="serie_description">
                Courte description (accroche) <span class="req">*</span>
                <?php if ($form->ERRORS && @$form->ERRORS_SCRIPT['serie_description'][0]): ?>
                  <span class="vf-err"><?= htmlspecialchars($form->ERRORS_SCRIPT['serie_description'][0]) ?></span>
                <?php endif; ?>
              </label>
              <textarea class="vf-textarea" name="register-serie_description" id="serie_description"
                        maxlength="300" rows="3"
                        placeholder="Résumé accrocheur visible dans la liste (max 300 caractères)..."><?= htmlspecialchars($d->serie_description ?? '') ?></textarea>
              <div class="vf-char-counter" id="vf-cc-short">0 / 300</div>
            </div>

            <div class="vf-field-group">
              <label class="vf-label" for="dtserie_description">Description détaillée</label>
              <textarea class="vf-textarea" name="register-dtserie_description" id="dtserie_description"
                        rows="8"
                        placeholder="Description complète du poste, missions, avantages, conditions..."><?= htmlspecialchars($d->dtserie_description ?? '') ?></textarea>
              <div class="vf-char-counter" id="vf-cc-long">0 / ∞</div>
            </div>

          </div>
          <div class="vf-form-actions">
            <button type="button" class="vf-btn vf-btn-outline" onclick="vfPrev(3)">
              <i class="fa fa-arrow-left vf-btn-icon"></i> Précédent
            </button>
            <button type="button" class="vf-btn vf-btn-primary" onclick="vfNext(3)">
              Suivant <i class="fa fa-arrow-right vf-btn-icon"></i>
            </button>
          </div>
        </div>

        <!-- ══════════════════════════════════════
             STEP 4 — Paramètres & Récapitulatif
        ══════════════════════════════════════ -->
        <div class="vf-card" id="vf-step-4">
          <div class="vf-card-header">
            <div class="vf-card-header-icon"><i class="fa fa-cog"></i></div>
            <div>
              <h2>Paramètres &amp; Récapitulatif</h2>
              <p>Visibilité, emplacement et enregistrement</p>
            </div>
          </div>
          <div class="vf-card-body">

            <div class="vf-field-row">
              <!-- Emplacement -->
              <div class="vf-field-group">
                <label class="vf-label" for="package">
                  Emplacement <span class="req">*</span>
                  <?php if ($form->ERRORS && @$form->ERRORS_SCRIPT['package'][0]): ?>
                    <span class="vf-err"><?= htmlspecialchars($form->ERRORS_SCRIPT['package'][0]) ?></span>
                  <?php endif; ?>
                </label>
                <select class="vf-select" name="register-package" id="package" required>
                  <option value="">— Sélectionnez —</option>
                  <option value="Normal_Blog"  <?= ($d->package ?? '') === 'Normal_Blog'  ? 'selected' : '' ?>>Page des offres</option>
                  <option value="To_home_Page" <?= ($d->package ?? '') === 'To_home_Page' ? 'selected' : '' ?>>Page d'accueil</option>
                </select>
              </div>
              <!-- Visibilité -->
              <div class="vf-field-group">
                <label class="vf-label" for="package_type">
                  Visibilité <span class="req">*</span>
                  <?php if ($form->ERRORS && @$form->ERRORS_SCRIPT['package_type'][0]): ?>
                    <span class="vf-err"><?= htmlspecialchars($form->ERRORS_SCRIPT['package_type'][0]) ?></span>
                  <?php endif; ?>
                </label>
                <select class="vf-select" name="register-package_type" id="package_type" required>
                  <option value="">— Sélectionnez —</option>
                  <option value="Public"  <?= ($d->package_type ?? '') === 'Public'  ? 'selected' : '' ?>>Publique</option>
                  <option value="Private" <?= ($d->package_type ?? '') === 'Private' ? 'selected' : '' ?>>Privée</option>
                </select>
              </div>
            </div>

            <!-- Récapitulatif -->
            <div class="vf-divider" style="margin-top:20px"><span>Récapitulatif</span></div>
            <div class="review-grid" id="vf-review-summary">
              <div class="review-item">
                <label>Titre du poste</label>
                <div class="value" id="rv-title">—</div>
              </div>
              <div class="review-item">
                <label>Entreprise</label>
                <div class="value" id="rv-company">—</div>
              </div>
              <div class="review-item">
                <label>Type d'emploi</label>
                <div class="value" id="rv-jobtype">—</div>
              </div>
              <div class="review-item">
                <label>Catégorie</label>
                <div class="value" id="rv-category">—</div>
              </div>
              <div class="review-item">
                <label>Localisation</label>
                <div class="value" id="rv-location">—</div>
              </div>
              <div class="review-item">
                <label>Expérience</label>
                <div class="value" id="rv-exprange">—</div>
              </div>
              <div class="review-item">
                <label>Langue</label>
                <div class="value" id="rv-lang">—</div>
              </div>
              <div class="review-item" style="grid-column:1/-1">
                <label>Compétences</label>
                <div class="review-tags" id="rv-comps"></div>
              </div>
              <div class="review-item" style="grid-column:1/-1">
                <label>Expériences</label>
                <div class="review-tags" id="rv-exps"></div>
              </div>
            </div>

          </div>
          <div class="vf-form-actions">
            <button type="button" class="vf-btn vf-btn-outline" onclick="vfPrev(4)">
              <i class="fa fa-arrow-left vf-btn-icon"></i> Précédent
            </button>
            <button type="submit" class="vf-btn vf-btn-primary vf-btn-lg" id="vf-submit-btn">
              <span class="vf-spinner"></span>
              <i class="fa fa-save vf-btn-icon"></i>
              Mettre à jour
            </button>
          </div>
        </div>

      </form>
    </div><!-- /.col-md-8 -->
  </div><!-- /.row form -->

</div><!-- /.container-fluid -->

<script>
/* ================================================================
   Multi-step navigation
================================================================ */
var vfCurrent = 1;
var VF_TOTAL  = 4;

function vfUpdateUI(step) {
  for (var i = 1; i <= VF_TOTAL; i++) {
    document.getElementById('vf-step-' + i).classList.toggle('active', i === step);
  }
  document.querySelectorAll('.vf-step').forEach(function(el) {
    var s = parseInt(el.dataset.step);
    el.classList.remove('active', 'done');
    if (s === step) el.classList.add('active');
    else if (s < step) el.classList.add('done');
  });
  var pct = Math.round((step / VF_TOTAL) * 100);
  document.getElementById('vf-progress-fill').style.width = pct + '%';
  document.getElementById('vf-progress-pct').textContent  = pct + '%';
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function vfGoTo(step) {
  if (step <= vfCurrent) { vfCurrent = step; vfUpdateUI(step); }
}

function vfNext(from) {
  if (!vfValidate(from)) return;
  if (from < VF_TOTAL) {
    vfCurrent = from + 1;
    if (vfCurrent === 4) vfBuildReview();
    vfUpdateUI(vfCurrent);
  }
}

function vfPrev(from) {
  if (from > 1) { vfCurrent = from - 1; vfUpdateUI(vfCurrent); }
}

/* ================================================================
   Validation — includes category, location
================================================================ */
function vfValidate(step) {
  vfClearErrors(step);
  var valid = true;

  if (step === 1) {
    valid = vfRequire('serie_title')       && valid;
    valid = vfRequire('company_name')      && valid;
    valid = vfRequireSelect('job_type')    && valid;
    valid = vfRequireSelect('category')    && valid; // NEW
    valid = vfRequire('location')          && valid; // NEW
  }
  if (step === 2) {
    var comps = [].filter.call(document.querySelectorAll('#vf-comp-list input'), function(i){ return i.value.trim(); });
    var exps  = [].filter.call(document.querySelectorAll('#vf-exp-list  input'), function(i){ return i.value.trim(); });
    if (!comps.length) { vfListError('vf-comp-list', 'Au moins une compétence est requise.'); valid = false; }
    if (!exps.length)  { vfListError('vf-exp-list',  'Au moins une expérience est requise.');  valid = false; }
    valid = vfRequire('education') && valid;
  }
  if (step === 3) valid = vfRequire('serie_description') && valid;
  if (step === 4) {
    valid = vfRequireSelect('package')      && valid;
    valid = vfRequireSelect('package_type') && valid;
  }
  return valid;
}

function vfRequire(id) {
  var el = document.getElementById(id);
  if (!el) return true;
  if (!el.value.trim()) {
    el.style.borderColor = 'var(--danger, #ef4444)';
    el.style.boxShadow   = '0 0 0 3px rgba(239,68,68,.1)';
    return false;
  }
  el.style.borderColor = '';
  el.style.boxShadow   = '';
  return true;
}
function vfRequireSelect(id) {
  var el = document.getElementById(id);
  if (!el) return true;
  if (!el.value) { el.style.borderColor = 'var(--danger, #ef4444)'; return false; }
  el.style.borderColor = '';
  return true;
}
function vfListError(listId, msg) {
  var list = document.getElementById(listId);
  var err  = list.parentNode.querySelector('.vf-list-err');
  if (!err) {
    err = document.createElement('p');
    err.className = 'vf-list-err';
    err.style.cssText = 'color:#ef4444;font-size:12px;margin-top:6px;';
    list.parentNode.insertBefore(err, list.nextElementSibling);
  }
  err.textContent = msg;
}
function vfClearErrors(step) {
  var card = document.getElementById('vf-step-' + step);
  card.querySelectorAll('.vf-list-err').forEach(function(el){ el.remove(); });
  card.querySelectorAll('.vf-input,.vf-select,.vf-textarea').forEach(function(el){
    el.style.borderColor = ''; el.style.boxShadow = '';
  });
}

/* ================================================================
   Dynamic lists
================================================================ */
var VF_LIMITS = { 'vf-comp-list': 7, 'vf-exp-list': 4 };

function vfAdd(listId, name, label) {
  var list  = document.getElementById(listId);
  var items = list.querySelectorAll('.vf-dynamic-item');
  var limit = VF_LIMITS[listId] || 10;
  if (items.length >= limit) { alert('Maximum ' + limit + ' entrées autorisées.'); return; }
  var idx  = items.length + 1;
  var item = document.createElement('div');
  item.className = 'vf-dynamic-item';
  item.draggable = true;
  item.innerHTML =
    '<span class="vf-drag-handle"><i class="fa fa-grip-vertical"></i></span>' +
    '<span class="vf-item-num">' + idx + '</span>' +
    '<input type="text" name="' + name + '" placeholder="' + label + ' ' + idx + '" maxlength="250">' +
    '<button type="button" class="vf-btn-remove" onclick="vfRemove(this,\'' + listId + '\')"><i class="fa fa-times"></i></button>';
  list.appendChild(item);
  item.querySelector('input').focus();
  vfBindDrag(item);
  vfRenum(listId);
}

function vfRemove(btn, listId) {
  var list = document.getElementById(listId);
  if (list.querySelectorAll('.vf-dynamic-item').length <= 1) {
    alert('Au moins une entrée est requise.'); return;
  }
  btn.closest('.vf-dynamic-item').remove();
  vfRenum(listId);
}

function vfRenum(listId) {
  document.getElementById(listId).querySelectorAll('.vf-item-num')
    .forEach(function(el, i){ el.textContent = i + 1; });
}

/* ================================================================
   Drag & drop
================================================================ */
var vfDragSrc = null;
function vfBindDrag(el) {
  el.addEventListener('dragstart', function(e) {
    vfDragSrc = this; e.dataTransfer.effectAllowed = 'move'; this.style.opacity = '.4';
  });
  el.addEventListener('dragend', function() {
    this.style.opacity = '';
    ['vf-comp-list','vf-exp-list'].forEach(vfRenum);
  });
  el.addEventListener('dragover',  function(e){ e.preventDefault(); e.dataTransfer.dropEffect = 'move'; return false; });
  el.addEventListener('drop', function(e) {
    e.stopPropagation(); e.preventDefault();
    if (vfDragSrc !== this) {
      var parent = this.parentNode;
      var srcIdx = Array.from(parent.children).indexOf(vfDragSrc);
      var dstIdx = Array.from(parent.children).indexOf(this);
      parent.insertBefore(vfDragSrc, srcIdx < dstIdx ? this.nextSibling : this);
    }
    return false;
  });
}
document.querySelectorAll('.vf-dynamic-item').forEach(vfBindDrag);

/* ================================================================
   Image preview
================================================================ */
document.getElementById('featuredImage').addEventListener('change', function() {
  var file = this.files[0]; if (!file) return;
  var reader = new FileReader();
  reader.onload = function(e) {
    document.getElementById('vf-preview-img').src = e.target.result;
    document.getElementById('vf-upload-preview').style.display = 'flex';
  };
  reader.readAsDataURL(file);
});
var vfZone = document.getElementById('vf-upload-zone');
vfZone.addEventListener('dragover',  function(e){ e.preventDefault(); vfZone.classList.add('dragover'); });
vfZone.addEventListener('dragleave', function(){ vfZone.classList.remove('dragover'); });
vfZone.addEventListener('drop', function(e) {
  e.preventDefault(); vfZone.classList.remove('dragover');
  document.getElementById('featuredImage').files = e.dataTransfer.files;
  document.getElementById('featuredImage').dispatchEvent(new Event('change'));
});

/* ================================================================
   Char counters
================================================================ */
function vfCounter(taId, ccId, max) {
  var ta = document.getElementById(taId), cc = document.getElementById(ccId);
  if (!ta || !cc) return;
  function upd() {
    var len = ta.value.length;
    cc.textContent = max ? len + ' / ' + max : len + ' / ∞';
    cc.className = 'vf-char-counter';
    if (max && len > max * .85) cc.classList.add('warn');
    if (max && len >= max)      cc.classList.add('over');
  }
  ta.addEventListener('input', upd); upd();
}
vfCounter('serie_description',   'vf-cc-short', 300);
vfCounter('dtserie_description', 'vf-cc-long',  0);

/* ================================================================
   Review builder — includes category, location, experience_range
================================================================ */
function vfBuildReview() {
  var g   = function(id){ return document.getElementById(id); };
  var val = function(id){ return g(id) ? g(id).value.trim() : ''; };
  var opt = function(id){
    var s = g(id);
    return s && s.selectedIndex > 0 ? s.options[s.selectedIndex].text : '—';
  };

  g('rv-title').textContent    = val('serie_title')  || '—';
  g('rv-company').textContent  = val('company_name') || '—';
  g('rv-jobtype').textContent  = opt('job_type');
  g('rv-category').textContent = opt('category');            // NEW
  g('rv-location').textContent = val('location')     || '—'; // NEW
  g('rv-exprange').textContent = opt('experience_range');    // NEW
  g('rv-lang').textContent     = opt('language');

  var ct = g('rv-comps'); ct.innerHTML = '';
  document.querySelectorAll('#vf-comp-list input').forEach(function(i) {
    if (i.value.trim()) {
      var t = document.createElement('span');
      t.className = 'review-tag'; t.textContent = i.value.trim(); ct.appendChild(t);
    }
  });
  if (!ct.children.length) ct.innerHTML = '<em style="font-size:12px;color:#aaa">Aucune saisie</em>';

  var et = g('rv-exps'); et.innerHTML = '';
  document.querySelectorAll('#vf-exp-list input').forEach(function(i) {
    if (i.value.trim()) {
      var t = document.createElement('span');
      t.className = 'review-tag exp'; t.textContent = i.value.trim(); et.appendChild(t);
    }
  });
  if (!et.children.length) et.innerHTML = '<em style="font-size:12px;color:#aaa">Aucune saisie</em>';
}

/* ================================================================
   Submit
================================================================ */
document.getElementById('vf-edit-form').addEventListener('submit', function(e) {
  if (!vfValidate(4)) { e.preventDefault(); return; }
  var btn = document.getElementById('vf-submit-btn');
  btn.classList.add('loading');
  btn.setAttribute('disabled', 'disabled');
});

var is_safari = navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1;
if (is_safari) {
  document.getElementById('vf-submit-btn').addEventListener('click', function() {
    this.querySelector('.vf-spinner').style.display = 'inline-block';
    this.querySelector('.vf-btn-icon').style.display = 'none';
  });
}
</script>