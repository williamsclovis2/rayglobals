<?php
/**
 * apply.php
 *
 * IMPORTANT — HOW init.php BREAKS base64 TOKENS:
 *
 * init.php parses $_GET by splitting each param on '=':
 *     $uri_get_el = explode('=', $uri_get_el);
 *     $_GET[$uri_get_el[0]] = @$uri_get_el[1];
 *
 * A base64 token like "MTAyMw==" in the URL becomes:
 *     $_GET['id'] = 'MTAyMw'   ← the '==' padding is lost!
 *
 * FIX: capture ?id= from the raw query string BEFORE init.php runs,
 * then pass the raw token directly to Hash::decryptToken().
 * We also use rawurlencode() when building redirect URLs so that
 * '=' chars become '%3D' and survive init.php's explode('=').
 *
 * CLASS FILES REQUIRED (place in admin/core/classes/):
 *   - JobApplication.php          (model)
 *   - JobApplicationController.php (controller)
 */

$page = "vacancies";

/* ══════════════════════════════════════════════════════════════
   STEP 1 — Grab the raw ?id= token BEFORE init.php rewrites $_GET
   We use the raw query string so base64 padding is preserved.
══════════════════════════════════════════════════════════════ */
$_RAW_QUERY = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';

// Extract raw ?id= value (preserves base64 '=' padding)
$_RAW_ID = '';
if (preg_match('/(?:^|&)id=([^&]+)/', $_RAW_QUERY, $_m)) {
    $_RAW_ID = urldecode($_m[1]);   // urldecode handles %3D → '=' if already encoded
}

// Also check for ?sent=1 before init.php touches it
$_SENT_RAW = '';
if (preg_match('/(?:^|&)sent=([^&]*)/', $_RAW_QUERY, $_sm)) {
    $_SENT_RAW = $_sm[1];
}

/* ══════════════════════════════════════════════════════════════
   STEP 2 — Bootstrap (loads DB, Session, Hash, Config, all classes)
   ob_start() is already called inside init.php so header() still works.
══════════════════════════════════════════════════════════════ */
require_once "admin/core/init.php";

/* ══════════════════════════════════════════════════════════════
   STEP 3 — Handle POST right here.
   apply.php is a PUBLIC page — the admin router switch never fires.
   We call JobApplicationController::submit() directly.
   Both class files must be in admin/core/classes/.
══════════════════════════════════════════════════════════════ */
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && !empty($_POST['register_submited'])
    && isset($_POST['request'])
    && $_POST['request'] === 'job-apply'
) {
    // The enc_id stored in the form was already rawurlencoded (see $safeEncId below),
    // so we can use it directly in the redirect URL.
    $enc_back = isset($_POST['job_enc_id']) ? $_POST['job_enc_id'] : '';

    try {
        $form = JobApplicationController::submit();

        if ($form->ERRORS == false) {
            // ✅ Success — set session flag, redirect with ?sent=1
            Session::put('app_sent', '1');
            header('Location: ' . DN . '/apply.php?id=' . $enc_back . '&sent=1');
            exit;
        } else {
            // ❌ Validation or upload error
            $errParts = [];
            if (is_array($form->ERRORS_SCRIPT)) {
                foreach ($form->ERRORS_SCRIPT as $v) {
                    $v = trim((string)$v);
                    if ($v !== '' && $v !== 'NO_ERRORS' && $v !== 'ERRORS_FOUND') {
                        $errParts[] = $v;
                    }
                }
            }
            $errMsg = !empty($errParts)
                ? implode(' ', $errParts)
                : 'Une erreur est survenue. Veuillez réessayer.';

            Session::put('errors', $errMsg);
            header('Location: ' . DN . '/apply.php?id=' . $enc_back);
            exit;
        }

    } catch (Throwable $e) {
        // ❌ Unexpected PHP exception — show it in a session error
        Session::put('errors',
            'Erreur serveur : ' . $e->getMessage()
            . ' — ' . basename($e->getFile()) . ':' . $e->getLine()
        );
        header('Location: ' . DN . '/apply.php?id=' . $enc_back);
        exit;
    }
}

/* ══════════════════════════════════════════════════════════════
   STEP 4 — Decrypt & validate the job ID
   Use $_RAW_ID (captured before init.php) so padding is intact.
══════════════════════════════════════════════════════════════ */
if (empty($_RAW_ID)) {
    header('Location: ' . DN . '/vacancies.php');
    exit;
}

$_ID = Hash::decryptToken($_RAW_ID);
if (empty($_ID) || !is_numeric($_ID)) {
    header('Location: ' . DN . '/vacancies.php');
    exit;
}

/* ══════════════════════════════════════════════════════════════
   STEP 5 — Load job data
══════════════════════════════════════════════════════════════ */
$storiSerieTable = new StoriSerie();
$storiSerieTable->selectQuery(
    "SELECT * FROM `stori_serie` WHERE `ID` = '" . (int)$_ID . "' LIMIT 1"
);
$job = $storiSerieTable->first();

if (!$job) {
    header('Location: ' . DN . '/vacancies.php');
    exit;
}

$jobId = (int)$job->ID;

/* ── Build a URL-safe version of the token for links & form hidden inputs.
   We use rawurlencode() so '=' becomes '%3D'.
   init.php's explode('=') then only splits on the literal '=' between
   the param name and value, leaving '%3D' untouched.
   Example:  ?id=MTAyMw%3D%3D   →  $_GET['id'] = 'MTAyMw%3D%3D'
             Hash::decryptToken(urldecode('MTAyMw%3D%3D')) = 1023  ✓
── */
$safeEncId = rawurlencode($_RAW_ID);   // '=' → '%3D', safe for URLs

/* ── Label maps ── */
$jobTypeLabels = [
    'tempsplein'    => 'Temps plein',
    'tempspartiel'  => 'Temps partiel',
    'roster'        => 'Roster / Consultant',
    'stage'         => 'Stage',
    'interim'       => 'Intérim',
    'apprentissage' => 'Apprentissage',
];
$categoryLabels = [
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
$jobTypeLabel = isset($jobTypeLabels[$job->job_type]) ? $jobTypeLabels[$job->job_type] : ($job->job_type ?? '');
$catLabel     = isset($categoryLabels[$job->category]) ? $categoryLabels[$job->category] : ($job->category ?? '');
$isNew        = !empty($job->posting_date)
                && strtotime($job->posting_date) >= strtotime('-3 days');

/* ══════════════════════════════════════════════════════════════
   STEP 6 — Popup & error state from session
══════════════════════════════════════════════════════════════ */
$justSent = ($_SENT_RAW === '1') || (Session::get('app_sent') === '1');
if ($justSent) {
    Session::delete('app_sent');
}

$sessionError = '';
if (Session::exists('errors')) {
    $sessionError = Session::get('errors');
    Session::delete('errors');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <?php include("includes/head.php") ?>
  <link rel="stylesheet" href="<?= DN ?>/assets/css/vacancies-new.css">
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&display=swap" rel="stylesheet">
</head>
<body class="hidden-bar-wrapper">
<div class="page-wrapper">

  <header class="main-header header-style-two">
    <?php include("includes/header.php") ?>
  </header>
  <div class="xs-sidebar-group info-group">
    <div class="xs-overlay xs-bg-black"></div>
    <?php include("includes/sidebar.php") ?>
  </div>

  <!-- Hero -->
  <div class="jd-hero">
    <div class="container">
      <div class="jd-hero-inner">

        <?php if (!empty($job->photo)): ?>
          <img class="jd-hero-logo"
               src="<?= DNADMIN . '/' . htmlspecialchars($job->photo) ?>"
               alt="<?= htmlspecialchars($job->company_name ?? '') ?>"
               onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
          <div class="jd-hero-logo-ph" style="display:none">
            <?= strtoupper(substr($job->company_name ?? 'J', 0, 1)) ?>
          </div>
        <?php else: ?>
          <div class="jd-hero-logo-ph">
            <?= strtoupper(substr($job->company_name ?? 'J', 0, 1)) ?>
          </div>
        <?php endif; ?>

        <div class="jd-hero-info">
          <div class="jd-hero-eyebrow">Postuler pour ce poste</div>
          <h1 class="jd-hero-title"><?= htmlspecialchars($job->serie_title) ?></h1>
          <div class="jd-hero-company">
            <i class="fa fa-building"></i> <?= htmlspecialchars($job->company_name) ?>
          </div>
          <div class="jd-hero-meta">
            <?php if (!empty($jobTypeLabel)): ?>
              <span class="jd-badge jdb-type"><i class="fa fa-briefcase"></i><?= htmlspecialchars($jobTypeLabel) ?></span>
            <?php endif; ?>
            <?php if (!empty($job->location)): ?>
              <span class="jd-badge jdb-loc"><i class="fa fa-map-marker-alt"></i><?= htmlspecialchars($job->location) ?></span>
            <?php endif; ?>
            <?php if (!empty($job->experience_range)): ?>
              <span class="jd-badge jdb-exp"><i class="fa fa-chart-line"></i><?= htmlspecialchars($job->experience_range) ?> ans</span>
            <?php endif; ?>
            <?php if (!empty($catLabel)): ?>
              <span class="jd-badge jdb-cat"><i class="fa fa-tag"></i><?= htmlspecialchars($catLabel) ?></span>
            <?php endif; ?>
            <?php if ($isNew): ?>
              <span class="jd-badge jdb-new">✦ Nouveau</span>
            <?php endif; ?>
          </div>
        </div>

        <?php if (!empty($job->views)): ?>
          <div class="jd-hero-actions">
            <span class="jd-views"><i class="fa fa-eye"></i> <?= (int)$job->views ?> vues</span>
          </div>
        <?php endif; ?>

      </div>
    </div>
    <div class="jd-wave"></div>
  </div>

  <!-- Main content -->
  <section class="jd-section">
    <div class="container">

      <a href="<?= DN ?>/vacancies-details.php?id=<?= $safeEncId ?>" class="jd-back">
        <i class="fa fa-arrow-left"></i> Voir le détail de l'offre
      </a>

      <div class="row">

        <!-- LEFT: Form -->
        <div class="col-xl-8 col-lg-8">

          <!-- Step progress -->
          <div class="vf-steps-card">
            <div class="vf-steps-header">
              <div class="vf-steps-header-left">
                <p>Progression</p>
                <h3>Votre candidature</h3>
              </div>
              <div class="vf-progress-inline">
                <div class="vf-progress-track">
                  <div class="vf-progress-fill" id="progress-fill"></div>
                </div>
                <span class="vf-progress-pct" id="progress-pct">50%</span>
              </div>
            </div>
            <div class="vf-steps-list">
              <div class="vf-step active" data-step="1" onclick="goToStep(1)">
                <div class="vf-step-num">1</div>
                <div class="vf-step-info">
                  <div class="vf-step-label">Informations personnelles</div>
                  <div class="vf-step-sub">Identité, contact &amp; profil</div>
                </div>
                <i class="fa fa-check vf-step-check"></i>
              </div>
              <div class="vf-step" data-step="2" onclick="goToStep(2)">
                <div class="vf-step-num">2</div>
                <div class="vf-step-info">
                  <div class="vf-step-label">Documents &amp; Motivation</div>
                  <div class="vf-step-sub">CV, diplômes &amp; lettre</div>
                </div>
                <i class="fa fa-check vf-step-check"></i>
              </div>
            </div>
          </div>

          <!-- Error banner -->
          <?php if (!empty($sessionError)): ?>
            <div style="margin-bottom:18px;padding:14px 18px;border-radius:12px;
                        background:#fef2f2;border:1px solid #fecaca;color:#dc2626;
                        display:flex;align-items:flex-start;gap:10px;font-size:14px;line-height:1.5">
              <i class="fa fa-exclamation-circle" style="margin-top:2px;flex-shrink:0"></i>
              <span><?= htmlspecialchars($sessionError) ?></span>
            </div>
          <?php endif; ?>

          <!--
            FORM — no action="" so it posts back to this same file.
            The POST block at the very top of this file handles submission.

            IMPORTANT: job_enc_id uses $safeEncId (rawurlencoded) so that
            when we read it back from $_POST['job_enc_id'] and put it in the
            redirect URL, init.php's explode('=') won't break the token.
          -->
          <form method="post" id="apply-form" enctype="multipart/form-data" novalidate>

            <input type="hidden" name="request"           value="job-apply">
            <input type="hidden" name="webToken"          value="<?= Config::get('time/seconds') ?>">
            <input type="hidden" name="register_submited" value="1">
            <input type="hidden" name="job_id"            value="<?= $jobId ?>">
            <input type="hidden" name="job_enc_id"        value="<?= $safeEncId ?>">
            <input type="hidden" name="job_title"         value="<?= htmlspecialchars($job->serie_title) ?>">
            <input type="hidden" name="company_name"      value="<?= htmlspecialchars($job->company_name) ?>">
            <input type="hidden" name="job_type"          value="<?= htmlspecialchars($job->job_type ?? '') ?>">
            <input type="hidden" name="job_location"      value="<?= htmlspecialchars($job->location ?? '') ?>">
            <input type="hidden" name="job_category"      value="<?= htmlspecialchars($job->category ?? '') ?>">

            <!-- ══ STEP 1 — Personal info ══ -->
            <div class="card active" id="step-1">
              <div class="card-header">
                <div class="card-header-icon"><i class="fa fa-user"></i></div>
                <div>
                  <h2>Informations personnelles</h2>
                  <p>Identité, coordonnées et profil professionnel</p>
                </div>
              </div>
              <div class="card-body">

                <div class="section-divider"><span>Identité</span></div>
                <div class="row g-3">
                  <div class="col-md-6">
                    <div class="field-group">
                      <label for="ap-firstname">Prénom <span class="req">*</span></label>
                      <input type="text" id="ap-firstname" name="register-firstname"
                             placeholder="Ex: Jean-Pierre" maxlength="80" autocomplete="given-name"
                             value="<?= htmlspecialchars($_POST['register-firstname'] ?? '') ?>">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="field-group">
                      <label for="ap-lastname">Nom <span class="req">*</span></label>
                      <input type="text" id="ap-lastname" name="register-lastname"
                             placeholder="Ex: Habimana" maxlength="80" autocomplete="family-name"
                             value="<?= htmlspecialchars($_POST['register-lastname'] ?? '') ?>">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="field-group">
                      <label for="ap-gender">Genre <span class="req">*</span></label>
                      <select id="ap-gender" name="register-gender">
                        <option value="">— Sélectionnez —</option>
                        <?php foreach (['Homme','Femme','Autre / Préfère ne pas préciser'] as $g): ?>
                          <option <?= (($_POST['register-gender'] ?? '') === $g) ? 'selected' : '' ?>><?= $g ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="field-group">
                      <label for="ap-dob">Date de naissance</label>
                      <input type="date" id="ap-dob" name="register-dob" autocomplete="bday"
                             value="<?= htmlspecialchars($_POST['register-dob'] ?? '') ?>">
                    </div>
                  </div>
                </div>

                <div class="section-divider"><span>Coordonnées</span></div>
                <div class="row g-3">
                  <div class="col-md-6">
                    <div class="field-group">
                      <label for="ap-email">Adresse e-mail <span class="req">*</span></label>
                      <input type="email" id="ap-email" name="register-email"
                             placeholder="prenom@exemple.com" autocomplete="email"
                             value="<?= htmlspecialchars($_POST['register-email'] ?? '') ?>">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="field-group">
                      <label for="ap-phone">Téléphone <span class="req">*</span></label>
                      <input type="tel" id="ap-phone" name="register-phone"
                             placeholder="+243 xxx xxx xxx" autocomplete="tel"
                             value="<?= htmlspecialchars($_POST['register-phone'] ?? '') ?>">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="field-group">
                      <label for="ap-country">Pays de résidence <span class="req">*</span></label>
                      <select id="ap-country" name="register-country">
                        <option value="">— Sélectionnez —</option>
                        <?php
                        $countries = ['RDC Congo','Rwanda','Burundi','Uganda','Kenya',
                                      'Tanzania','Cameroun',"Côte d'Ivoire",'Sénégal',
                                      'Belgique','France','Autre'];
                        foreach ($countries as $c): ?>
                          <option <?= (($_POST['register-country'] ?? '') === $c) ? 'selected' : '' ?>><?= $c ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="field-group">
                      <label for="ap-city">Ville <span class="req">*</span></label>
                      <input type="text" id="ap-city" name="register-city"
                             placeholder="Ex: Kinshasa, Goma…" maxlength="100"
                             value="<?= htmlspecialchars($_POST['register-city'] ?? '') ?>">
                    </div>
                  </div>
                </div>

                <div class="section-divider"><span>Profil professionnel</span></div>
                <div class="row g-3">
                  <div class="col-md-6">
                    <div class="field-group">
                      <label for="ap-education">Niveau d'études <span class="req">*</span></label>
                      <select id="ap-education" name="register-education">
                        <option value="">— Sélectionnez —</option>
                        <?php
                        $eduOpts = ['Brevet / Collège','Baccalauréat / Secondaire',
                                    'Licence / Bachelor (Bac +3)','Master (Bac +5)',
                                    'Doctorat (Bac +8)','Formation professionnelle / Certificat','Autre'];
                        foreach ($eduOpts as $e): ?>
                          <option <?= (($_POST['register-education'] ?? '') === $e) ? 'selected' : '' ?>><?= $e ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="field-group">
                      <label for="ap-experience">Années d'expérience <span class="req">*</span></label>
                      <select id="ap-experience" name="register-experience">
                        <option value="">— Sélectionnez —</option>
                        <?php
                        $expOpts = ["Moins d'1 an (Junior)",'1 – 2 ans','2 – 3 ans',
                                    '3 – 6 ans','Plus de 6 ans (Senior)'];
                        foreach ($expOpts as $e): ?>
                          <option <?= (($_POST['register-experience'] ?? '') === $e) ? 'selected' : '' ?>><?= $e ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="field-group">
                      <label for="ap-current-position">Poste actuel / dernier poste</label>
                      <input type="text" id="ap-current-position" name="register-current_position"
                             placeholder="Ex: Développeur Backend chez XYZ" maxlength="150"
                             value="<?= htmlspecialchars($_POST['register-current_position'] ?? '') ?>">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="field-group">
                      <label for="ap-linkedin">LinkedIn <small style="color:var(--muted);font-weight:400">(optionnel)</small></label>
                      <input type="url" id="ap-linkedin" name="register-linkedin"
                             placeholder="https://linkedin.com/in/votre-profil"
                             value="<?= htmlspecialchars($_POST['register-linkedin'] ?? '') ?>">
                    </div>
                  </div>
                </div>

              </div>
              <div class="form-actions">
                <a href="<?= DN ?>/vacancies-details.php?id=<?= $safeEncId ?>" class="btn btn-outline">
                  <i class="fa fa-times btn-icon"></i> Annuler
                </a>
                <button type="button" class="btn btn-primary" onclick="nextStep(1)">
                  Suivant <i class="fa fa-arrow-right btn-icon"></i>
                </button>
              </div>
            </div>

            <!-- ══ STEP 2 — Documents ══ -->
            <div class="card" id="step-2">
              <div class="card-header">
                <div class="card-header-icon"><i class="fa fa-folder-open"></i></div>
                <div>
                  <h2>Documents &amp; Motivation</h2>
                  <p>CV, lettre de motivation, diplômes et message</p>
                </div>
              </div>
              <div class="card-body">

                <div class="jid-notice">
                  <i class="fa fa-briefcase" style="color:var(--navy)"></i>
                  Candidature pour : <strong><?= htmlspecialchars($job->serie_title) ?></strong>
                  &nbsp;·&nbsp; <?= htmlspecialchars($job->company_name) ?>
                </div>

                <div class="section-divider"><span>CV — Curriculum Vitae</span></div>
                <div class="field-group">
                  <label>Téléverser votre CV <span class="req">*</span></label>
                  <div class="upload-zone" id="uz-cv">
                    <input type="file" name="file_cv" id="file-cv" accept=".pdf,.doc,.docx"
                           onchange="handleSingleFile(this,'uz-cv','chip-cv','chip-cv-name','chip-cv-size','fa-file-pdf')">
                    <i class="fa fa-file-alt uz-icon"></i>
                    <span class="uz-title">Glissez votre CV ici ou <strong>cliquez pour parcourir</strong></span>
                    <span class="uz-hint">PDF, DOC, DOCX · Max 5 Mo</span>
                  </div>
                  <div class="file-chip" id="chip-cv">
                    <i class="fa fa-file-pdf fc-icon"></i>
                    <span class="fc-name" id="chip-cv-name">—</span>
                    <span class="fc-size" id="chip-cv-size"></span>
                    <button type="button" class="fc-remove" onclick="clearFile('file-cv','uz-cv','chip-cv')">
                      <i class="fa fa-times"></i>
                    </button>
                  </div>
                  <span class="hint-text">Un seul fichier. PDF, DOC ou DOCX. Taille max : 5 Mo.</span>
                </div>

                <div class="section-divider"><span>Lettre de motivation (fichier)</span></div>
                <div class="field-group">
                  <label>Téléverser votre lettre <small style="color:var(--muted);font-weight:400">(optionnel)</small></label>
                  <div class="upload-zone" id="uz-cover">
                    <input type="file" name="file_cover" id="file-cover" accept=".pdf,.doc,.docx"
                           onchange="handleSingleFile(this,'uz-cover','chip-cover','chip-cover-name','chip-cover-size','fa-file-word')">
                    <i class="fa fa-file-signature uz-icon"></i>
                    <span class="uz-title">Glissez votre lettre ici ou <strong>cliquez pour parcourir</strong></span>
                    <span class="uz-hint">PDF, DOC, DOCX · Max 5 Mo</span>
                  </div>
                  <div class="file-chip" id="chip-cover">
                    <i class="fa fa-file-word fc-icon" style="color:var(--navy-mid)"></i>
                    <span class="fc-name" id="chip-cover-name">—</span>
                    <span class="fc-size" id="chip-cover-size"></span>
                    <button type="button" class="fc-remove" onclick="clearFile('file-cover','uz-cover','chip-cover')">
                      <i class="fa fa-times"></i>
                    </button>
                  </div>
                </div>

                <div class="section-divider"><span>Diplômes &amp; Certifications</span></div>
                <div class="field-group">
                  <label>Ajouter vos diplômes <small style="color:var(--muted);font-weight:400">(optionnel — plusieurs fichiers)</small></label>
                  <div class="upload-zone" id="uz-diplomas"
                       onclick="document.getElementById('file-diplomas-trigger').click()">
                    <i class="fa fa-graduation-cap uz-icon"></i>
                    <span class="uz-title" id="uz-diplomas-title">Cliquez pour ajouter un diplôme ou une certification</span>
                    <span class="uz-hint">PDF, JPG, PNG · Max 5 Mo par fichier</span>
                  </div>
                  <input type="file" id="file-diplomas-trigger" style="display:none"
                         name="file_diplomas[]" accept=".pdf,.jpg,.jpeg,.png" multiple
                         onchange="addDiplomas(this)">
                  <div class="diplomas-list" id="diplomas-list"></div>
                  <button type="button" class="btn-add-item"
                          onclick="document.getElementById('file-diplomas-trigger').click()">
                    <i class="fa fa-plus"></i> Ajouter un autre document
                  </button>
                </div>

                <div class="section-divider"><span>Message de motivation</span></div>
                <div class="field-group">
                  <label for="ap-message">Lettre de motivation (texte) <small style="color:var(--muted);font-weight:400">(optionnel)</small></label>
                  <textarea id="ap-message" name="register-message" rows="6"
                            placeholder="Présentez-vous et expliquez pourquoi ce poste vous correspond…"><?= htmlspecialchars($_POST['register-message'] ?? '') ?></textarea>
                  <div class="char-counter" id="cc-message">0 / 2000</div>
                </div>

              </div>
              <div class="form-actions">
                <button type="button" class="btn btn-outline" onclick="prevStep(2)">
                  <i class="fa fa-arrow-left btn-icon"></i> Précédent
                </button>
                <button type="submit" class="btn-submit-app" id="submit-btn">
                  <span class="spinner"></span>
                  <i class="fa fa-paper-plane"></i> Envoyer ma candidature
                </button>
              </div>
            </div>

          </form>

        </div><!-- /.col-xl-8 -->

        <!-- RIGHT: Overview sidebar -->
        <div class="col-xl-4 col-lg-4">

          <a href="<?= DN ?>/vacancies-details.php?id=<?= $safeEncId ?>" class="jd-detail-link">
            <i class="fa fa-file-alt" style="color:var(--accent)"></i>
            Voir la description complète
            <i class="fa fa-external-link-alt ms-auto" style="font-size:11px;color:var(--muted)"></i>
          </a>

          <div class="jd-overview-card">
            <div class="jd-overview-header"><i class="fa fa-info-circle"></i> Aperçu de l'offre</div>
            <div class="jd-overview-body">

              <?php if (!empty($job->posting_date)): ?>
              <div class="jd-ov-row">
                <div class="jd-ov-icon"><i class="fa fa-calendar-alt"></i></div>
                <div><div class="jd-ov-label">Date de publication</div>
                     <div class="jd-ov-value"><?= date('d M Y', strtotime($job->posting_date)) ?></div></div>
              </div>
              <?php endif; ?>

              <?php if (!empty($job->location)): ?>
              <div class="jd-ov-row">
                <div class="jd-ov-icon"><i class="fa fa-map-marker-alt"></i></div>
                <div><div class="jd-ov-label">Localisation</div>
                     <div class="jd-ov-value"><?= htmlspecialchars($job->location) ?></div></div>
              </div>
              <?php endif; ?>

              <?php if (!empty($jobTypeLabel)): ?>
              <div class="jd-ov-row">
                <div class="jd-ov-icon"><i class="fa fa-briefcase"></i></div>
                <div><div class="jd-ov-label">Type de contrat</div>
                     <div class="jd-ov-value"><?= htmlspecialchars($jobTypeLabel) ?></div></div>
              </div>
              <?php endif; ?>

              <?php if (!empty($job->experience_range)): ?>
              <div class="jd-ov-row">
                <div class="jd-ov-icon"><i class="fa fa-chart-line"></i></div>
                <div><div class="jd-ov-label">Expérience requise</div>
                     <div class="jd-ov-value"><?= htmlspecialchars($job->experience_range) ?> ans</div></div>
              </div>
              <?php endif; ?>

              <?php if (!empty($job->education)): ?>
              <div class="jd-ov-row">
                <div class="jd-ov-icon"><i class="fa fa-graduation-cap"></i></div>
                <div><div class="jd-ov-label">Éducation requise</div>
                     <div class="jd-ov-value"><?= htmlspecialchars($job->education) ?></div></div>
              </div>
              <?php endif; ?>

              <?php if (!empty($catLabel)): ?>
              <div class="jd-ov-row">
                <div class="jd-ov-icon"><i class="fa fa-tag"></i></div>
                <div><div class="jd-ov-label">Catégorie</div>
                     <div class="jd-ov-value"><?= htmlspecialchars($catLabel) ?></div></div>
              </div>
              <?php endif; ?>

              <?php if (!empty($job->language)): ?>
              <div class="jd-ov-row">
                <div class="jd-ov-icon"><i class="fa fa-globe"></i></div>
                <div><div class="jd-ov-label">Langue</div>
                     <div class="jd-ov-value"><?= htmlspecialchars($job->language) ?></div></div>
              </div>
              <?php endif; ?>

              <?php if (!empty($job->views)): ?>
              <div class="jd-ov-row">
                <div class="jd-ov-icon"><i class="fa fa-eye"></i></div>
                <div><div class="jd-ov-label">Vues</div>
                     <div class="jd-ov-value"><?= (int)$job->views ?></div></div>
              </div>
              <?php endif; ?>

            </div>
          </div>

        </div>
      </div>
    </div>
  </section>

  <footer class="footer-style-two" style="background-image:url(assets/images/background/10.jpg)">
    <?php include("includes/footer.php") ?>
  </footer>
</div>

<div class="scroll-to-top scroll-to-target" data-target="html"><span class="fa fa-arrow-up"></span></div>
<?php include("includes/script.php") ?>

<!-- ══════════════════════════════════════════════════════════════
     SUCCESS POPUP
══════════════════════════════════════════════════════════════ -->
<?php if ($justSent): ?>
<style>
@keyframes apPopIn   { from{opacity:0;transform:scale(.82) translateY(28px)} to{opacity:1;transform:scale(1) translateY(0)} }
@keyframes apCheckIn { from{opacity:0;transform:scale(.4) rotate(-20deg)}    to{opacity:1;transform:scale(1) rotate(0)}     }
</style>
<div id="app-success-overlay"
     style="display:flex;position:fixed;inset:0;z-index:99999;
            background:rgba(10,10,20,.65);backdrop-filter:blur(7px);
            align-items:center;justify-content:center;padding:20px">
  <div style="background:#fff;border-radius:22px;padding:48px 42px 42px;
              max-width:500px;width:100%;text-align:center;
              box-shadow:0 32px 90px rgba(0,0,0,.28);
              animation:apPopIn .42s cubic-bezier(.34,1.56,.64,1) both">

    <div style="width:82px;height:82px;border-radius:50%;
                background:linear-gradient(135deg,#22c55e,#16a34a);
                display:flex;align-items:center;justify-content:center;
                margin:0 auto 24px;box-shadow:0 12px 32px rgba(34,197,94,.38);
                animation:apCheckIn .45s .18s cubic-bezier(.34,1.56,.64,1) both">
      <svg width="38" height="38" viewBox="0 0 24 24" fill="none"
           stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="20 6 9 17 4 12"></polyline>
      </svg>
    </div>

    <h3 style="font-size:24px;font-weight:800;color:#0f172a;margin:0 0 10px;  ">
      Candidature envoyée !
    </h3>
    <p style="font-size:15px;color:#475569;line-height:1.7;margin:0 0 6px">
      Votre candidature pour le poste de<br>
      <strong style="color:#0f172a"><?= htmlspecialchars($job->serie_title) ?></strong><br>
      chez <strong style="color:#0f172a"><?= htmlspecialchars($job->company_name) ?></strong>
      a bien été reçue.
    </p>
    <p style="font-size:13px;color:#94a3b8;margin:0 0 32px;
              display:flex;align-items:center;justify-content:center;gap:6px">
      <i class="fa fa-envelope"></i>
      Vérifiez votre boîte e-mail pour plus d'informations.
    </p>

    <div style="display:flex;flex-direction:column;gap:12px;align-items:center">
      <!-- Primary: back to vacancies list -->
      <a href="<?= DN ?>/vacancies.php"
   style="display:inline-flex;align-items:center;justify-content:center;gap:9px;
          width:100%;max-width:320px;padding:15px 28px;border-radius:50px;
          background:linear-gradient(135deg,#f5a623,#ffb347);
          color:#fff;font-weight:700;font-size:15px;text-decoration:none;
          box-shadow:0 8px 22px rgba(245,166,35,.35);
          transition:transform .2s,box-shadow .2s"
   onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 12px 30px rgba(245,166,35,.55)'"
   onmouseout="this.style.transform='';this.style.boxShadow='0 8px 22px rgba(245,166,35,.35)'">
   <i class="fa fa-arrow-left"></i> Retour à la page des offres
</a>
      <!-- Secondary: close and stay -->
      <button onclick="closeSuccessPopup()"
              style="display:inline-flex;align-items:center;justify-content:center;gap:8px;
                     width:100%;max-width:320px;padding:13px 28px;border-radius:50px;
                     border:2px solid #e2e8f0;background:#fff;color:#64748b;
                     font-weight:600;font-size:14px;cursor:pointer;transition:border-color .2s,color .2s"
              onmouseover="this.style.borderColor='#94a3b8';this.style.color='#1e293b'"
              onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#64748b'">
        <i class="fa fa-times"></i> Fermer
      </button>
    </div>
  </div>
</div>
<script>
document.body.style.overflow = 'hidden';
function closeSuccessPopup() {
  document.getElementById('app-success-overlay').style.display = 'none';
  document.body.style.overflow = '';
  if (window.history && window.history.replaceState) {
    var clean = window.location.href
      .replace(/([?&])sent=1(&?)/, function(_, p, s) { return s ? p : ''; })
      .replace(/[?&]$/, '');
    window.history.replaceState({}, document.title, clean);
  }
}
document.getElementById('app-success-overlay').addEventListener('click', function(e) {
  if (e.target === this) closeSuccessPopup();
});
</script>
<?php endif; ?>

<script>
var currentStep=1,TOTAL_STEPS=2;
function updateUI(s){for(var i=1;i<=TOTAL_STEPS;i++)document.getElementById('step-'+i).classList.toggle('active',i===s);document.querySelectorAll('.vf-step').forEach(function(el){var n=parseInt(el.dataset.step);el.classList.remove('active','done');if(n===s)el.classList.add('active');else if(n<s)el.classList.add('done')});var p=Math.round(s/TOTAL_STEPS*100);document.getElementById('progress-fill').style.width=p+'%';document.getElementById('progress-pct').textContent=p+'%';window.scrollTo({top:0,behavior:'smooth'})}
function goToStep(s){if(s<=currentStep){currentStep=s;updateUI(s)}}
function nextStep(f){if(!validateStep(f))return;if(f<TOTAL_STEPS){currentStep=f+1;updateUI(currentStep)}}
function prevStep(f){if(f>1){currentStep=f-1;updateUI(currentStep)}}
function validateStep(s){clearErrors(s);var v=true;if(s===1){v=apReq('ap-firstname')&&v;v=apReq('ap-lastname')&&v;v=apReqSel('ap-gender')&&v;v=apReqEmail('ap-email')&&v;v=apReq('ap-phone')&&v;v=apReqSel('ap-country')&&v;v=apReq('ap-city')&&v;v=apReqSel('ap-education')&&v;v=apReqSel('ap-experience')&&v}if(s===2){var cv=document.getElementById('file-cv');if(!cv||!cv.files||!cv.files.length){var uz=document.getElementById('uz-cv');uz.style.borderColor='#ef4444';uz.style.borderStyle='solid';uz.scrollIntoView({behavior:'smooth',block:'center'});v=false}}return v}
function apReq(id){var el=document.getElementById(id);if(!el||!el.value.trim()){if(el)el.classList.add('error');return false}el.classList.remove('error');return true}
function apReqSel(id){var el=document.getElementById(id);if(!el||!el.value){if(el)el.classList.add('error');return false}el.classList.remove('error');return true}
function apReqEmail(id){var el=document.getElementById(id);if(!el||!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(el.value.trim())){if(el)el.classList.add('error');return false}el.classList.remove('error');return true}
function clearErrors(s){var c=document.getElementById('step-'+s);if(c)c.querySelectorAll('.error').forEach(function(el){el.classList.remove('error')});var uz=document.getElementById('uz-cv');if(uz){uz.style.borderColor='';uz.style.borderStyle=''}}
function handleSingleFile(input,zId,cId,nId,sId,ic){var f=input.files[0];if(!f)return;var z=document.getElementById(zId);z.classList.add('has-file');z.style.borderColor='';z.style.borderStyle='';document.getElementById(nId).textContent=f.name;document.getElementById(sId).textContent=fmtSz(f.size);var ch=document.getElementById(cId);ch.classList.add('visible');ch.querySelector('.fc-icon').className='fc-icon fa '+ic}
function clearFile(iId,zId,cId){document.getElementById(iId).value='';document.getElementById(zId).classList.remove('has-file');document.getElementById(cId).classList.remove('visible')}
var diplomaFiles=[];
function addDiplomas(inp){var list=document.getElementById('diplomas-list'),uz=document.getElementById('uz-diplomas'),title=document.getElementById('uz-diplomas-title');Array.from(inp.files).forEach(function(f){if(diplomaFiles.some(function(x){return x.name===f.name&&x.size===f.size}))return;diplomaFiles.push(f);var ext=f.name.split('.').pop().toLowerCase(),ic=ext==='pdf'?'fa-file-pdf':(ext==='jpg'||ext==='jpeg'||ext==='png')?'fa-file-image':'fa-file-word',ch=document.createElement('div');ch.className='diploma-chip';ch.innerHTML='<i class="fa '+ic+' fc-icon"></i><span class="fc-name" title="'+escH(f.name)+'">'+escH(f.name)+'</span><span class="fc-size">'+fmtSz(f.size)+'</span><button type="button" class="fc-remove" onclick="rmDiploma(this,\''+escH(f.name)+'\')"><i class="fa fa-times"></i></button>';list.appendChild(ch)});var n=list.querySelectorAll('.diploma-chip').length;if(n){title.textContent=n+" document(s) ajouté(s) — cliquez pour en ajouter d'autres";uz.classList.add('has-file')}inp.value=''}
function rmDiploma(btn,name){diplomaFiles=diplomaFiles.filter(function(f){return f.name!==name});btn.closest('.diploma-chip').remove();var list=document.getElementById('diplomas-list'),title=document.getElementById('uz-diplomas-title'),uz=document.getElementById('uz-diplomas'),n=list.querySelectorAll('.diploma-chip').length;if(!n){title.textContent='Cliquez pour ajouter un diplôme ou une certification';uz.classList.remove('has-file')}else title.textContent=n+' document(s) ajouté(s)'}
['uz-cv','uz-cover'].forEach(function(zId){var z=document.getElementById(zId);z.addEventListener('dragover',function(e){e.preventDefault();z.classList.add('dragover')});z.addEventListener('dragleave',function(){z.classList.remove('dragover')});z.addEventListener('drop',function(e){e.preventDefault();z.classList.remove('dragover');var id=zId==='uz-cv'?'file-cv':'file-cover';try{var dt=new DataTransfer();dt.items.add(e.dataTransfer.files[0]);document.getElementById(id).files=dt.files;document.getElementById(id).dispatchEvent(new Event('change'))}catch(err){}})});
var uzD=document.getElementById('uz-diplomas');uzD.addEventListener('dragover',function(e){e.preventDefault();uzD.classList.add('dragover')});uzD.addEventListener('dragleave',function(){uzD.classList.remove('dragover')});uzD.addEventListener('drop',function(e){e.preventDefault();uzD.classList.remove('dragover');addDiplomas({files:e.dataTransfer.files,value:''})});
var mA=document.getElementById('ap-message'),mC=document.getElementById('cc-message');if(mA){mA.addEventListener('input',function(){var l=mA.value.length;mC.textContent=l+' / 2000';mC.className='char-counter'+(l>1800?' warn':'')+(l>=2000?' over':'');if(l>2000)mA.value=mA.value.slice(0,2000)})}
document.getElementById('apply-form').addEventListener('submit',function(e){if(!validateStep(2)){e.preventDefault();return}/* FIX: Re-populate file_diplomas[] input from in-memory diplomaFiles array before submit. The addDiplomas() function clears the input after each add to allow re-selection, so on submit the input is empty. We rebuild it here using DataTransfer. */if(diplomaFiles.length>0){try{var dt=new DataTransfer();diplomaFiles.forEach(function(f){dt.items.add(f)});document.getElementById('file-diplomas-trigger').files=dt.files}catch(dtErr){/* DataTransfer not supported — fallback: files already in input from last selection */console.warn('DataTransfer not supported:',dtErr)}}var btn=document.getElementById('submit-btn');btn.classList.add('loading');btn.disabled=true});
function fmtSz(b){if(b<1024)return b+' B';if(b<1048576)return(b/1024).toFixed(1)+' Ko';return(b/1048576).toFixed(1)+' Mo'}
function escH(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;')}
</script>
</body>
</html>