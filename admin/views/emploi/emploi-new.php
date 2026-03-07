<?php
/**
 * vacancy-form.php
 * Admin form — create or edit a vacancy (stori_serie).
 * Drop this file where your original form lived.
 *
 * NEW FIELDS vs. original:
 *   Step 1 — category, location
 *   Step 2 — experience_range  (replaces the free-text "expérience" guess)
 */

$edit_mode = isset($stori_serie_data) && !empty($stori_serie_data);
$d         = $edit_mode ? $stori_serie_data : (object) [];

/* ── helper: repopulate after error ── */
function old(string $field, $default = ''): string
{
    if (isset($GLOBALS['form']) && $GLOBALS['form']->ERRORS
        && Input::get("register-{$field}", 'post') !== null) {
        return htmlspecialchars((string) Input::get("register-{$field}", 'post'));
    }
    return htmlspecialchars((string) $default);
}

/* ── rebuild competences / experiences arrays ── */
$competences = [];
if (isset($GLOBALS['form']) && $GLOBALS['form']->ERRORS && !empty($_POST['register-competences'])) {
    foreach ((array) $_POST['register-competences'] as $v) {
        $clean = trim($v);
        if ($clean !== '') $competences[] = htmlspecialchars($clean);
    }
} else {
    for ($i = 1; $i <= 7; $i++) {
        $key = "comp_{$i}";
        $val = $edit_mode ? (isset($d->$key) ? trim($d->$key) : '') : '';
        if ($val !== '') $competences[] = htmlspecialchars($val);
    }
}
if (empty($competences)) $competences = [''];

$experiences = [];
if (isset($GLOBALS['form']) && $GLOBALS['form']->ERRORS && !empty($_POST['register-experiences'])) {
    foreach ((array) $_POST['register-experiences'] as $v) {
        $clean = trim($v);
        if ($clean !== '') $experiences[] = htmlspecialchars($clean);
    }
} else {
    for ($i = 1; $i <= 4; $i++) {
        $key = "exp_{$i}";
        $val = $edit_mode ? (isset($d->$key) ? trim($d->$key) : '') : '';
        if ($val !== '') $experiences[] = htmlspecialchars($val);
    }
}
if (empty($experiences)) $experiences = [''];

/* ── shared option sets ── */
$jobTypes = [
    'tempsplein'    => 'Temps plein',
    'tempspartiel'  => 'Temps partiel',
    'roster'     => 'Roster / Consultant',
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
     'chauffeur'    => 'Chauffeur',
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

$langs    = ['Kiswahili' => 'KISWAHILI', 'FRANCAIS' => 'Français', 'ENGLISH' => 'English'];
$packages = ['Normal_Blog' => 'Page des offres', 'To_home_Page' => "Page d'accueil"];
$ptypes   = ['Public' => 'Publique', 'Private' => 'Privée'];
?>

<!-- ── Page Header ── -->
<div class="page-header">
  <h1>
    <?php if ($edit_mode): ?>
      Modifier l'offre <span>#<?= htmlspecialchars($d->ID) ?></span>
    <?php else: ?>
      Nouvelle <span>offre d'emploi</span>
    <?php endif; ?>
  </h1>
  <div class="header-actions">
    <a href="<?= DNADMIN ?>/app/emploi/list" class="btn-back">
      <i class="fa fa-arrow-left"></i> Liste des offres
    </a>
  </div>
</div>

<div class="container-fluid" style="padding: 28px 20px 80px; max-width: 1140px;">

  <!-- Steps bar -->
  <div class="row">
    <div class="col-md-12">
      <div class="vf-steps-card" id="steps-sidebar">
        <div class="vf-steps-header">
          <div class="vf-steps-header-left">
            <p>Progression</p>
            <h3><?= $edit_mode ? "Modifier l'offre" : 'Nouvelle offre' ?></h3>
          </div>
          <div class="vf-progress-inline">
            <div class="vf-progress-track">
              <div class="vf-progress-fill" id="progress-fill"></div>
            </div>
            <span class="vf-progress-pct" id="progress-pct">25%</span>
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
          <div class="vf-step <?= $num === 1 ? 'active' : '' ?>" data-step="<?= $num ?>" onclick="goToStep(<?= $num ?>)">
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

  <!-- Form -->
  <div class="row" style="margin-top: 4px;">
    <div class="col-md-8">

      <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success" style="margin-bottom:16px">
          <i class="fa fa-check-circle"></i>
          <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($_SESSION['errors'])): ?>
        <div class="alert alert-error" style="margin-bottom:16px">
          <i class="fa fa-exclamation-circle"></i>
          <?= htmlspecialchars($_SESSION['errors']); unset($_SESSION['errors']); ?>
        </div>
      <?php endif; ?>

      <form method="post" id="vacancy-form" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="request"           value="<?= $edit_mode ? 'serie-edit' : 'serie-new' ?>">
        <input type="hidden" name="webToken"          value="<?= Config::get('time/seconds') ?>">
        <input type="hidden" name="register_submited" value="1">
        <?php if ($edit_mode): ?>
        <input type="hidden" name="serie_id" value="<?= (int) $d->ID ?>">
        <?php endif; ?>

        <!-- ══════════════════════════════════════
             STEP 1 — Job Information
        ══════════════════════════════════════ -->
        <div class="card active" id="step-1">
          <div class="card-header">
            <div class="card-header-icon"><i class="fa fa-briefcase"></i></div>
            <div>
              <h2>Informations sur le poste</h2>
              <p>Titre, entreprise, catégorie, localisation</p>
            </div>
          </div>
          <div class="card-body">

            <!-- Row 1: Title + Company -->
            <div class="field-row">
              <div class="field-group">
                <label for="serie_title">
                  Titre du poste <span class="req">*</span>
                  <?php if (isset($form) && $form->ERRORS): ?>
                    <span class="err-msg"><?= htmlspecialchars(@$form->ERRORS_SCRIPT['serie_title'][0]) ?></span>
                  <?php endif; ?>
                </label>
                <input type="text" id="serie_title" name="register-serie_title"
                       placeholder="Ex: Développeur Full-Stack Senior"
                       value="<?= old('serie_title', $d->serie_title ?? '') ?>"
                       maxlength="150" required>
              </div>
              <div class="field-group">
                <label for="company_name">
                  Nom de l'entreprise <span class="req">*</span>
                  <?php if (isset($form) && $form->ERRORS): ?>
                    <span class="err-msg"><?= htmlspecialchars(@$form->ERRORS_SCRIPT['company_name'][0]) ?></span>
                  <?php endif; ?>
                </label>
                <input type="text" id="company_name" name="register-company_name"
                       placeholder="Ex: Kigali Tech Ltd"
                       value="<?= old('company_name', $d->company_name ?? '') ?>"
                       maxlength="150" required>
              </div>
            </div>

            <!-- Row 2: Job Type + Language -->
            <div class="field-row">
              <div class="field-group">
                <label for="job_type">
                  Type d'emploi <span class="req">*</span>
                  <?php if (isset($form) && $form->ERRORS): ?>
                    <span class="err-msg"><?= htmlspecialchars(@$form->ERRORS_SCRIPT['job_type'][0]) ?></span>
                  <?php endif; ?>
                </label>
                <select id="job_type" name="register-job_type" required>
                  <option value="">— Sélectionnez —</option>
                  <?php $sel = old('job_type', $d->job_type ?? '');
                  foreach ($jobTypes as $val => $label): ?>
                    <option value="<?= $val ?>" <?= $sel === $val ? 'selected' : '' ?>><?= $label ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="field-group">
                <label for="language">Langue de l'annonce</label>
                <select id="language" name="register-language">
                  <?php $sel = old('language', $d->language ?? 'KINYARWANDA');
                  foreach ($langs as $val => $label): ?>
                    <option value="<?= $val ?>" <?= $sel === $val ? 'selected' : '' ?>><?= $label ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <!-- Row 3: Category + Location  ← NEW -->
            <div class="field-row">
              <div class="field-group">
                <label for="category">
                  Catégorie <span class="req">*</span>
                  <?php if (isset($form) && $form->ERRORS): ?>
                    <span class="err-msg"><?= htmlspecialchars(@$form->ERRORS_SCRIPT['category'][0]) ?></span>
                  <?php endif; ?>
                </label>
                <select id="category" name="register-category" required>
                  <option value="">— Sélectionnez —</option>
                  <?php $sel = old('category', $d->category ?? '');
                  foreach ($categories as $val => $label): ?>
                    <option value="<?= $val ?>" <?= $sel === $val ? 'selected' : '' ?>><?= $label ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="field-group">
                <label for="location">
                  Localisation <span class="req">*</span>
                  <?php if (isset($form) && $form->ERRORS): ?>
                    <span class="err-msg"><?= htmlspecialchars(@$form->ERRORS_SCRIPT['location'][0]) ?></span>
                  <?php endif; ?>
                </label>
                <input type="text" id="location" name="register-location"
                       placeholder="Ex: Kigali, Gasabo"
                       value="<?= old('location', $d->location ?? '') ?>"
                       maxlength="150" required>
              </div>
            </div>

            <!-- Logo upload -->
            <div class="field-group" style="margin-top:8px">
              <label for="featuredImage">
                Logo de l'entreprise <span class="req">*</span>
              </label>
              <?php if ($edit_mode && !empty($d->photo)): ?>
              <div class="existing-photo">
                <img src="<?= DNADMIN . '/' . htmlspecialchars($d->photo) ?>" alt="Logo actuel">
                <span>Logo actuel — téléversez une nouvelle image pour remplacer</span>
              </div>
              <?php endif; ?>
              <div class="upload-zone" id="upload-zone">
                <input type="file" name="featuredImage" id="featuredImage"
                       accept="image/png,image/jpeg,image/gif"
                       <?= $edit_mode ? '' : 'required' ?>>
                <div class="icon"><i class="fa fa-cloud-upload-alt"></i></div>
                <div class="upload-text">Glissez une image ici, ou <strong>cliquez pour parcourir</strong></div>
                <div class="upload-hint">PNG, JPG, GIF · Max 5 Mo</div>
              </div>
              <div class="upload-preview" id="upload-preview">
                <img id="preview-img" src="" alt="Prévisualisation">
              </div>
            </div>

          </div>
          <div class="form-actions">
            <a href="<?= DNADMIN ?>/app/serie/list" class="btn btn-outline">
              <i class="fa fa-times btn-icon"></i> Annuler
            </a>
            <button type="button" class="btn btn-primary" onclick="nextStep(1)">
              Suivant <i class="fa fa-arrow-right btn-icon"></i>
            </button>
          </div>
        </div>

        <!-- ══════════════════════════════════════
             STEP 2 — Competences & Experience
        ══════════════════════════════════════ -->
        <div class="card" id="step-2">
          <div class="card-header">
            <div class="card-header-icon"><i class="fa fa-star"></i></div>
            <div>
              <h2>Compétences & Expériences</h2>
              <p>Ajoutez, supprimez ou réordonnez librement</p>
            </div>
          </div>
          <div class="card-body">

            <!-- Competences dynamic list -->
            <div class="section-divider"><span>Compétences requises</span></div>
            <div class="dynamic-list" id="competences-list">
              <?php foreach (array_values($competences) as $idx => $val): ?>
              <div class="dynamic-item" draggable="true">
                <span class="drag-handle"><i class="fa fa-grip-vertical"></i></span>
                <span class="item-num"><?= $idx + 1 ?></span>
                <input type="text" name="register-competences[]"
                       placeholder="Ex: Maîtrise de JavaScript"
                       value="<?= $val ?>" maxlength="250">
                <button type="button" class="btn-remove-item"
                        onclick="removeItem(this,'competences-list')" title="Supprimer">
                  <i class="fa fa-times"></i>
                </button>
              </div>
              <?php endforeach; ?>
            </div>
            <button type="button" class="btn-add-item"
                    onclick="addItem('competences-list','register-competences[]','Compétence')">
              <i class="fa fa-plus"></i> Ajouter une compétence
            </button>
            <div class="list-limit-note">
              <i class="fa fa-info-circle"></i> Maximum 7 compétences. Au moins 1 requise.
            </div>

            <!-- Education -->
            <div class="section-divider" style="margin-top:28px"><span>Éducation</span></div>
            <div class="field-row full">
              <div class="field-group">
                <label for="education">
                  Niveau d'éducation requis <span class="req">*</span>
                  <?php if (isset($form) && $form->ERRORS): ?>
                    <span class="err-msg"><?= htmlspecialchars(@$form->ERRORS_SCRIPT['education'][0]) ?></span>
                  <?php endif; ?>
                </label>
                <input type="text" id="education" name="register-education"
                       placeholder="Ex: Licence en Informatique ou équivalent"
                       value="<?= old('education', $d->education ?? '') ?>"
                       maxlength="250" required>
              </div>
            </div>

            <!-- Experience range  ← NEW dropdown -->
            <div class="section-divider" style="margin-top:10px"><span>Années d'expérience requises</span></div>
            <div class="field-row">
              <div class="field-group">
                <label for="experience_range">
                  Tranche d'expérience
                  <small style="color:#888;font-weight:400">(utilisée pour le filtre du site)</small>
                </label>
                <select id="experience_range" name="register-experience_range">
                  <option value="">— Sélectionnez —</option>
                  <?php $sel = old('experience_range', $d->experience_range ?? '');
                  foreach ($expRanges as $val => $label): ?>
                    <option value="<?= $val ?>" <?= $sel === $val ? 'selected' : '' ?>><?= $label ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <!-- Experiences dynamic list -->
            <div class="section-divider" style="margin-top:10px"><span>Détails d'expériences requises</span></div>
            <div class="dynamic-list" id="experiences-list">
              <?php foreach (array_values($experiences) as $idx => $val): ?>
              <div class="dynamic-item" draggable="true">
                <span class="drag-handle"><i class="fa fa-grip-vertical"></i></span>
                <span class="item-num"><?= $idx + 1 ?></span>
                <input type="text" name="register-experiences[]"
                       placeholder="Ex: 3 ans d'expérience en développement web"
                       value="<?= $val ?>" maxlength="250">
                <button type="button" class="btn-remove-item"
                        onclick="removeItem(this,'experiences-list')" title="Supprimer">
                  <i class="fa fa-times"></i>
                </button>
              </div>
              <?php endforeach; ?>
            </div>
            <button type="button" class="btn-add-item"
                    onclick="addItem('experiences-list','register-experiences[]','Expérience')">
              <i class="fa fa-plus"></i> Ajouter une expérience
            </button>
            <div class="list-limit-note">
              <i class="fa fa-info-circle"></i> Maximum 4 expériences. Au moins 1 requise.
            </div>

          </div>
          <div class="form-actions">
            <button type="button" class="btn btn-outline" onclick="prevStep(2)">
              <i class="fa fa-arrow-left btn-icon"></i> Précédent
            </button>
            <button type="button" class="btn btn-primary" onclick="nextStep(2)">
              Suivant <i class="fa fa-arrow-right btn-icon"></i>
            </button>
          </div>
        </div>

        <!-- ══════════════════════════════════════
             STEP 3 — Descriptions
        ══════════════════════════════════════ -->
        <div class="card" id="step-3">
          <div class="card-header">
            <div class="card-header-icon"><i class="fa fa-align-left"></i></div>
            <div>
              <h2>Descriptions</h2>
              <p>Courte accroche et détails complets du poste</p>
            </div>
          </div>
          <div class="card-body">

            <div class="field-group" style="margin-bottom:16px">
              <label for="serie_description">
                Courte description (accroche) <span class="req">*</span>
                <?php if (isset($form) && $form->ERRORS): ?>
                  <span class="err-msg"><?= htmlspecialchars(@$form->ERRORS_SCRIPT['serie_description'][0]) ?></span>
                <?php endif; ?>
              </label>
              <textarea id="serie_description" name="register-serie_description"
                        maxlength="300" rows="3"
                        placeholder="Résumé accrocheur visible dans la liste (max 300 caractères)..."><?= old('serie_description', $d->serie_description ?? '') ?></textarea>
              <div class="char-counter" id="cc-short">0 / 300</div>
            </div>

            <div class="field-group">
              <label for="dtserie_description">Description détaillée</label>
              <textarea id="dtserie_description" name="register-dtserie_description"
                        rows="8"
                        placeholder="Description complète du poste, missions, avantages, conditions..."><?= old('dtserie_description', $d->dtserie_description ?? '') ?></textarea>
              <div class="char-counter" id="cc-long">0 / ∞</div>
            </div>

          </div>
          <div class="form-actions">
            <button type="button" class="btn btn-outline" onclick="prevStep(3)">
              <i class="fa fa-arrow-left btn-icon"></i> Précédent
            </button>
            <button type="button" class="btn btn-primary" onclick="nextStep(3)">
              Suivant <i class="fa fa-arrow-right btn-icon"></i>
            </button>
          </div>
        </div>

        <!-- ══════════════════════════════════════
             STEP 4 — Settings & Review
        ══════════════════════════════════════ -->
        <div class="card" id="step-4">
          <div class="card-header">
            <div class="card-header-icon"><i class="fa fa-cog"></i></div>
            <div>
              <h2>Paramètres & Publication</h2>
              <p>Visibilité, emplacement et soumission</p>
            </div>
          </div>
          <div class="card-body">

            <div class="field-row">
              <div class="field-group">
                <label for="package">
                  Emplacement <span class="req">*</span>
                  <?php if (isset($form) && $form->ERRORS): ?>
                    <span class="err-msg"><?= htmlspecialchars(@$form->ERRORS_SCRIPT['package'][0]) ?></span>
                  <?php endif; ?>
                </label>
                <select id="package" name="register-package" required>
                  <option value="">— Sélectionnez —</option>
                  <?php $sel = old('package', $d->package ?? '');
                  foreach ($packages as $val => $label): ?>
                    <option value="<?= $val ?>" <?= $sel === $val ? 'selected' : '' ?>><?= $label ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="field-group">
                <label for="package_type">
                  Visibilité <span class="req">*</span>
                  <?php if (isset($form) && $form->ERRORS): ?>
                    <span class="err-msg"><?= htmlspecialchars(@$form->ERRORS_SCRIPT['package_type'][0]) ?></span>
                  <?php endif; ?>
                </label>
                <select id="package_type" name="register-package_type" required>
                  <option value="">— Sélectionnez —</option>
                  <?php $sel = old('package_type', $d->package_type ?? '');
                  foreach ($ptypes as $val => $label): ?>
                    <option value="<?= $val ?>" <?= $sel === $val ? 'selected' : '' ?>><?= $label ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <!-- Review summary -->
            <div class="section-divider" style="margin-top:20px"><span>Récapitulatif</span></div>
            <div class="review-grid" id="review-summary">
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
          <div class="form-actions">
            <button type="button" class="btn btn-outline" onclick="prevStep(4)">
              <i class="fa fa-arrow-left btn-icon"></i> Précédent
            </button>
            <button type="submit" class="btn btn-primary btn-lg" id="submit-btn">
              <span class="spinner"></span>
              <i class="fa fa-paper-plane btn-icon"></i>
              <?= $edit_mode ? "Mettre à jour" : "Publier l'offre" ?>
            </button>
          </div>
        </div>

      </form>
    </div><!-- /.col-md-8 -->
  </div><!-- /.row (form) -->

</div><!-- /.container-fluid -->

<script>
/* ================================================================
   Multi-step navigation
================================================================ */
let currentStep  = 1;
const TOTAL_STEPS = 4;

function updateUI(step) {
  for (let i = 1; i <= TOTAL_STEPS; i++) {
    document.getElementById('step-' + i).classList.toggle('active', i === step);
  }
  document.querySelectorAll('.vf-step').forEach(el => {
    const s = parseInt(el.dataset.step);
    el.classList.remove('active', 'done');
    if (s === step)  el.classList.add('active');
    else if (s < step) el.classList.add('done');
  });
  const pct = Math.round((step / TOTAL_STEPS) * 100);
  document.getElementById('progress-fill').style.width = pct + '%';
  document.getElementById('progress-pct').textContent  = pct + '%';
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function goToStep(step) {
  if (step <= currentStep) { currentStep = step; updateUI(step); }
}
function nextStep(from) {
  if (!validateStep(from)) return;
  if (from < TOTAL_STEPS) {
    currentStep = from + 1;
    if (currentStep === 4) buildReview();
    updateUI(currentStep);
  }
}
function prevStep(from) {
  if (from > 1) { currentStep = from - 1; updateUI(currentStep); }
}

/* ================================================================
   Validation — includes new fields: category, location
================================================================ */
function validateStep(step) {
  clearErrors(step);
  let valid = true;

  if (step === 1) {
    valid = requireField('serie_title')  && valid;
    valid = requireField('company_name') && valid;
    valid = requireSelect('job_type')    && valid;
    valid = requireSelect('category')    && valid;  // NEW
    valid = requireField('location')     && valid;  // NEW
  }
  if (step === 2) {
    const comps = [...document.querySelectorAll('#competences-list input')].filter(i => i.value.trim());
    const exps  = [...document.querySelectorAll('#experiences-list input')].filter(i => i.value.trim());
    if (!comps.length) { showListError('competences-list', 'Au moins une compétence est requise.'); valid = false; }
    if (!exps.length)  { showListError('experiences-list', 'Au moins une expérience est requise.');  valid = false; }
    valid = requireField('education') && valid;
  }
  if (step === 3) valid = requireField('serie_description') && valid;
  if (step === 4) {
    valid = requireSelect('package')      && valid;
    valid = requireSelect('package_type') && valid;
  }
  return valid;
}

function requireField(id) {
  const el = document.getElementById(id);
  if (!el) return true;
  if (!el.value.trim()) { el.classList.add('error'); return false; }
  el.classList.remove('error'); return true;
}
function requireSelect(id) {
  const el = document.getElementById(id);
  if (!el) return true;
  if (!el.value) { el.classList.add('error'); return false; }
  el.classList.remove('error'); return true;
}
function showListError(listId, msg) {
  const list = document.getElementById(listId);
  let err = list.parentNode.querySelector('.list-err');
  if (!err) {
    err = document.createElement('p');
    err.className = 'list-err';
    err.style.cssText = 'color:#ef4444;font-size:12px;margin-top:6px;';
    list.parentNode.insertBefore(err, list.nextElementSibling);
  }
  err.textContent = msg;
}
function clearErrors(step) {
  const card = document.getElementById('step-' + step);
  card.querySelectorAll('.error').forEach(el => el.classList.remove('error'));
  card.querySelectorAll('.list-err').forEach(el => el.remove());
}

/* ================================================================
   Dynamic lists
================================================================ */
const LIMITS = { 'competences-list': 7, 'experiences-list': 4 };

function addItem(listId, name, label) {
  const list  = document.getElementById(listId);
  const items = list.querySelectorAll('.dynamic-item');
  const limit = LIMITS[listId] || 10;
  if (items.length >= limit) { alert('Maximum ' + limit + ' entrées autorisées.'); return; }
  const idx  = items.length + 1;
  const item = document.createElement('div');
  item.className = 'dynamic-item';
  item.draggable = true;
  item.innerHTML = `
    <span class="drag-handle"><i class="fa fa-grip-vertical"></i></span>
    <span class="item-num">${idx}</span>
    <input type="text" name="${name}" placeholder="${label} ${idx}" maxlength="250">
    <button type="button" class="btn-remove-item" onclick="removeItem(this,'${listId}')" title="Supprimer">
      <i class="fa fa-times"></i>
    </button>`;
  list.appendChild(item);
  item.querySelector('input').focus();
  bindDrag(item);
  renumberList(listId);
}

function removeItem(btn, listId) {
  const list = document.getElementById(listId);
  if (list.querySelectorAll('.dynamic-item').length <= 1) {
    alert('Au moins une entrée est requise.'); return;
  }
  btn.closest('.dynamic-item').remove();
  renumberList(listId);
}

function renumberList(listId) {
  document.getElementById(listId).querySelectorAll('.item-num')
    .forEach((el, i) => { el.textContent = i + 1; });
}

/* ================================================================
   Drag & drop
================================================================ */
let dragSrc = null;
function bindDrag(el) {
  el.addEventListener('dragstart', function(e) {
    dragSrc = this; e.dataTransfer.effectAllowed = 'move'; this.style.opacity = '.4';
  });
  el.addEventListener('dragend', function() {
    this.style.opacity = '';
    ['competences-list', 'experiences-list'].forEach(renumberList);
  });
  el.addEventListener('dragover',  e => { e.preventDefault(); e.dataTransfer.dropEffect = 'move'; });
  el.addEventListener('drop', function(e) {
    e.stopPropagation(); e.preventDefault();
    if (dragSrc !== this) {
      const parent = this.parentNode;
      const srcIdx = [...parent.children].indexOf(dragSrc);
      const dstIdx = [...parent.children].indexOf(this);
      parent.insertBefore(dragSrc, srcIdx < dstIdx ? this.nextSibling : this);
    }
  });
}
document.querySelectorAll('.dynamic-item').forEach(bindDrag);

/* ================================================================
   Image preview
================================================================ */
document.getElementById('featuredImage').addEventListener('change', function() {
  const file = this.files[0]; if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    document.getElementById('preview-img').src = e.target.result;
    document.getElementById('upload-preview').style.display = 'flex';
  };
  reader.readAsDataURL(file);
});
const zone = document.getElementById('upload-zone');
zone.addEventListener('dragover',  e => { e.preventDefault(); zone.classList.add('dragover'); });
zone.addEventListener('dragleave', ()  => zone.classList.remove('dragover'));
zone.addEventListener('drop', e => {
  e.preventDefault(); zone.classList.remove('dragover');
  document.getElementById('featuredImage').files = e.dataTransfer.files;
  document.getElementById('featuredImage').dispatchEvent(new Event('change'));
});

/* ================================================================
   Char counters
================================================================ */
function bindCounter(taId, ccId, max) {
  const ta = document.getElementById(taId), cc = document.getElementById(ccId);
  if (!ta || !cc) return;
  const update = () => {
    const len = ta.value.length;
    cc.textContent = max ? `${len} / ${max}` : `${len} / ∞`;
    cc.className = 'char-counter';
    if (max && len > max * .85) cc.classList.add('warn');
    if (max && len >= max)      cc.classList.add('over');
  };
  ta.addEventListener('input', update); update();
}
bindCounter('serie_description',   'cc-short', 300);
bindCounter('dtserie_description', 'cc-long',  0);

/* ================================================================
   Review builder — now includes category, location, experience_range
================================================================ */
function buildReview() {
  const g   = id => document.getElementById(id);
  const val = id => g(id) ? g(id).value.trim() : '';
  const opt = id => {
    const s = g(id);
    return s && s.selectedIndex > 0 ? s.options[s.selectedIndex].text : '—';
  };

  g('rv-title').textContent    = val('serie_title')  || '—';
  g('rv-company').textContent  = val('company_name') || '—';
  g('rv-jobtype').textContent  = opt('job_type');
  g('rv-category').textContent = opt('category');           // NEW
  g('rv-location').textContent = val('location')    || '—'; // NEW
  g('rv-exprange').textContent = opt('experience_range');   // NEW
  g('rv-lang').textContent     = opt('language');

  const ct = g('rv-comps'); ct.innerHTML = '';
  document.querySelectorAll('#competences-list input').forEach(i => {
    if (i.value.trim()) {
      const t = document.createElement('span');
      t.className = 'review-tag'; t.textContent = i.value.trim(); ct.appendChild(t);
    }
  });
  if (!ct.children.length) ct.innerHTML = '<em style="font-size:12px;color:#aaa">Aucune saisie</em>';

  const et = g('rv-exps'); et.innerHTML = '';
  document.querySelectorAll('#experiences-list input').forEach(i => {
    if (i.value.trim()) {
      const t = document.createElement('span');
      t.className = 'review-tag exp'; t.textContent = i.value.trim(); et.appendChild(t);
    }
  });
  if (!et.children.length) et.innerHTML = '<em style="font-size:12px;color:#aaa">Aucune saisie</em>';
}

/* ================================================================
   Submit
================================================================ */
document.getElementById('vacancy-form').addEventListener('submit', function(e) {
  if (!validateStep(4)) { e.preventDefault(); return; }
  const btn = document.getElementById('submit-btn');
  btn.classList.add('loading');
  btn.setAttribute('disabled', 'disabled');
});
</script>