<?php
/**
 * vacancies-details.php — Professional job detail page.
 * "Postuler maintenant" buttons now link to apply.php?id=...
 */
$page = "vacancies";

/* Read id from RAW query string before init.php explode("=") mangles it */
$_RAW_QUERY = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
$_ID_ = '';
if (preg_match('/(?:^|&)id=([^&]*)/', $_RAW_QUERY, $_m)) {
    $_ID_ = urldecode($_m[1]);
}

require_once "admin/core/init.php";

if (empty($_ID_)) { header('Location: ' . DN . '/vacancies.php'); exit; }

$_ID = Hash::decryptToken($_ID_);
if (empty($_ID) || !is_numeric($_ID)) { header('Location: ' . DN . '/vacancies.php'); exit; }

$storiSerieTable = new StoriSerie();
$storiSerieTable->selectQuery("SELECT * FROM `stori_serie` WHERE id='" . (int)$_ID . "'");
$stori_serie_data = $storiSerieTable->first();

if (!$stori_serie_data) { header('Location: ' . DN . '/vacancies.php'); exit; }

/* Build apply URL — rtrim strips encoded "=" padding that breaks init re-parse */
$applyUrl = DN . '/apply?id=' . urlencode($_ID_);

// Build competences & experiences arrays
$comps = [];
for ($i = 1; $i <= 7; $i++) {
    $k = "comp_{$i}";
    if (!empty(trim($stori_serie_data->$k ?? ''))) $comps[] = trim($stori_serie_data->$k);
}
$exps = [];
for ($i = 1; $i <= 4; $i++) {
    $k = "exp_{$i}";
    if (!empty(trim($stori_serie_data->$k ?? ''))) $exps[] = trim($stori_serie_data->$k);
}

$jobTypeLabels = [
    'tempsplein' => 'Temps plein', 'tempspartiel' => 'Temps partiel',
    'roster' => 'Roster / Consultant', 'stage' => 'Stage',
    'interim' => 'Intérim', 'apprentissage' => 'Apprentissage',
];
$categoryLabels = [
    'informatique' => 'Informatique / Tech', 'finance' => 'Finance / Comptabilité',
    'marketing' => 'Marketing / Communication', 'sante' => 'Santé',
    'education' => 'Éducation', 'Chauffeur' => 'Chauffeur',
    'construction' => 'Construction / BTP', 'agriculture' => 'Agriculture', 'autre' => 'Autre',
];

$jobTypeLabel = $jobTypeLabels[$stori_serie_data->job_type ?? ''] ?? ($stori_serie_data->job_type ?? '');
$catLabel     = $categoryLabels[$stori_serie_data->category ?? ''] ?? ($stori_serie_data->category ?? '');
$isNew        = !empty($stori_serie_data->posting_date) && strtotime($stori_serie_data->posting_date) >= strtotime('-3 days');

/* ── Apply URL — passes the same encrypted id to apply.php ── */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <?php include("includes/head.php") ?>
  <link rel="stylesheet" href="<?= DN ?>/assets/css/vacancies-new.css">
  <style>
  /* Ensure apply links render as styled buttons not plain text */
  a.jd-apply-btn, a.jd-apply-card-btn {
    display: inline-flex !important;
    align-items: center;
    gap: 8px;
    text-decoration: none !important;
    cursor: pointer;
  }
  </style>
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

  <!-- Hero banner -->
  <div class="jd-hero">
    <div class="container">
      <div class="jd-hero-inner">
        <!-- Logo -->
        <?php if (!empty($stori_serie_data->photo)): ?>
          <img class="jd-hero-logo"
               src="<?= DNADMIN . '/' . htmlspecialchars($stori_serie_data->photo) ?>"
               alt="<?= htmlspecialchars($stori_serie_data->company_name ?? '') ?>"
               onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
          <div class="jd-hero-logo-ph" style="display:none">
            <?= strtoupper(substr($stori_serie_data->company_name ?? 'J', 0, 1)) ?>
          </div>
        <?php else: ?>
          <div class="jd-hero-logo-ph">
            <?= strtoupper(substr($stori_serie_data->company_name ?? 'J', 0, 1)) ?>
          </div>
        <?php endif; ?>

        <!-- Info -->
        <div class="jd-hero-info">
          <h1 class="jd-hero-title"><?= htmlspecialchars($stori_serie_data->serie_title) ?></h1>
          <div class="jd-hero-company">
            <i class="fa fa-building"></i>
            <?= htmlspecialchars($stori_serie_data->company_name) ?>
          </div>
          <div class="jd-hero-meta">
            <?php if (!empty($jobTypeLabel)): ?>
              <span class="jd-badge jdb-type"><i class="fa fa-briefcase"></i><?= htmlspecialchars($jobTypeLabel) ?></span>
            <?php endif; ?>
            <?php if (!empty($stori_serie_data->location)): ?>
              <span class="jd-badge jdb-loc"><i class="fa fa-map-marker-alt"></i><?= htmlspecialchars($stori_serie_data->location) ?></span>
            <?php endif; ?>
            <?php if (!empty($stori_serie_data->experience_range)): ?>
              <span class="jd-badge jdb-exp"><i class="fa fa-chart-line"></i><?= htmlspecialchars($stori_serie_data->experience_range) ?> ans exp.</span>
            <?php endif; ?>
            <?php if (!empty($catLabel)): ?>
              <span class="jd-badge jdb-cat"><i class="fa fa-tag"></i><?= htmlspecialchars($catLabel) ?></span>
            <?php endif; ?>
            <?php if ($isNew): ?>
              <span class="jd-badge jdb-new">✦ Nouveau</span>
            <?php endif; ?>
          </div>
        </div>

        <!-- CTA — now an <a> linking to apply.php with the encrypted id -->
        <div class="jd-hero-actions">
          <a href="<?= $applyUrl ?>" class="jd-apply-btn">
            <i class="fa fa-paper-plane"></i> Postuler maintenant
          </a>
          <?php if (!empty($stori_serie_data->views)): ?>
            <span class="jd-views"><i class="fa fa-eye"></i> <?= (int)$stori_serie_data->views ?> vues</span>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <div class="jd-wave"></div>
  </div>

  <!-- Main content -->
  <section class="jd-section">
    <div class="container">
      <a href="vacancies.php" class="jd-back"><i class="fa fa-arrow-left"></i> Retour aux offres</a>

      <div class="row">
        <!-- Left column -->
        <div class="col-xl-8 col-lg-8">

          <!-- Description -->
          <?php if (!empty($stori_serie_data->dtserie_description)): ?>
          <div class="jd-card">
            <div class="jd-card-header">
              <div class="jd-card-header-icon"><i class="fa fa-align-left"></i></div>
              <h3>Description du poste</h3>
            </div>
            <div class="jd-card-body">
              <p class="jd-desc"><?= nl2br(htmlspecialchars($stori_serie_data->dtserie_description)) ?></p>
            </div>
          </div>
          <?php endif; ?>

          <!-- Competences -->
          <?php if (!empty($comps)): ?>
          <div class="jd-card">
            <div class="jd-card-header">
              <div class="jd-card-header-icon"><i class="fa fa-star"></i></div>
              <h3>Compétences et connaissances requises</h3>
            </div>
            <div class="jd-card-body">
              <ul class="jd-list">
                <?php foreach ($comps as $c): ?>
                  <li><?= htmlspecialchars($c) ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
          <?php endif; ?>

          <!-- Education + Experience -->
          <div class="jd-card">
            <div class="jd-card-header">
              <div class="jd-card-header-icon"><i class="fa fa-graduation-cap"></i></div>
              <h3>Éducation &amp; Expérience</h3>
            </div>
            <div class="jd-card-body">
              <ul class="jd-list">
                <?php if (!empty($stori_serie_data->education)): ?>
                  <li><?= htmlspecialchars($stori_serie_data->education) ?></li>
                <?php endif; ?>
                <?php foreach ($exps as $e): ?>
                  <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>

          <!-- Share -->
          <div class="jd-card">
            <div class="jd-card-header">
              <div class="jd-card-header-icon"><i class="fa fa-share-alt"></i></div>
              <h3>Partager cette offre</h3>
            </div>
            <div class="jd-card-body">
              <div class="jd-share">
                <a href="#" class="jd-share-btn jsb-fb"><i class="fab fa-facebook-f"></i> Facebook</a>
                <a href="#" class="jd-share-btn jsb-tw"><i class="fab fa-twitter"></i> Twitter</a>
                <a href="#" class="jd-share-btn jsb-ln"><i class="fab fa-linkedin-in"></i> LinkedIn</a>
                <button onclick="navigator.clipboard.writeText(window.location.href)" class="jd-share-btn jsb-cp">
                  <i class="fa fa-link"></i> Copier le lien
                </button>
              </div>
            </div>
          </div>

        </div>
        <!-- /Left column -->

        <!-- Right column -->
        <div class="col-xl-4 col-lg-4">

          <!-- Apply card — now links to apply.php with encrypted id -->
          <div class="jd-apply-card">
            <h4>Intéressé(e) par ce poste ?</h4>
            <p>Soumettez votre candidature dès maintenant.</p>
            <a href="<?= $applyUrl ?>" class="jd-apply-card-btn">
              <i class="fa fa-paper-plane"></i> Postuler maintenant
            </a>
          </div>

          <!-- Overview -->
          <div class="jd-overview-card">
            <div class="jd-overview-header">
              <i class="fa fa-info-circle"></i> Aperçu de l'offre
            </div>
            <div class="jd-overview-body">

              <?php if (!empty($stori_serie_data->posting_date)): ?>
              <div class="jd-ov-row">
                <div class="jd-ov-icon"><i class="fa fa-calendar-alt"></i></div>
                <div>
                  <div class="jd-ov-label">Date de publication</div>
                  <div class="jd-ov-value"><?= date('d M Y', strtotime($stori_serie_data->posting_date)) ?></div>
                </div>
              </div>
              <?php endif; ?>

              <?php if (!empty($stori_serie_data->location)): ?>
              <div class="jd-ov-row">
                <div class="jd-ov-icon"><i class="fa fa-map-marker-alt"></i></div>
                <div>
                  <div class="jd-ov-label">Localisation</div>
                  <div class="jd-ov-value"><?= htmlspecialchars($stori_serie_data->location) ?></div>
                </div>
              </div>
              <?php endif; ?>

              <?php if (!empty($jobTypeLabel)): ?>
              <div class="jd-ov-row">
                <div class="jd-ov-icon"><i class="fa fa-briefcase"></i></div>
                <div>
                  <div class="jd-ov-label">Type de contrat</div>
                  <div class="jd-ov-value"><?= htmlspecialchars($jobTypeLabel) ?></div>
                </div>
              </div>
              <?php endif; ?>

              <?php if (!empty($stori_serie_data->experience_range)): ?>
              <div class="jd-ov-row">
                <div class="jd-ov-icon"><i class="fa fa-chart-line"></i></div>
                <div>
                  <div class="jd-ov-label">Expérience requise</div>
                  <div class="jd-ov-value"><?= htmlspecialchars($stori_serie_data->experience_range) ?> ans</div>
                </div>
              </div>
              <?php endif; ?>

              <?php if (!empty($stori_serie_data->education)): ?>
              <div class="jd-ov-row">
                <div class="jd-ov-icon"><i class="fa fa-graduation-cap"></i></div>
                <div>
                  <div class="jd-ov-label">Éducation</div>
                  <div class="jd-ov-value"><?= htmlspecialchars($stori_serie_data->education) ?></div>
                </div>
              </div>
              <?php endif; ?>

              <?php if (!empty($catLabel)): ?>
              <div class="jd-ov-row">
                <div class="jd-ov-icon"><i class="fa fa-tag"></i></div>
                <div>
                  <div class="jd-ov-label">Catégorie</div>
                  <div class="jd-ov-value"><?= htmlspecialchars($catLabel) ?></div>
                </div>
              </div>
              <?php endif; ?>

              <?php if (!empty($stori_serie_data->language)): ?>
              <div class="jd-ov-row">
                <div class="jd-ov-icon"><i class="fa fa-globe"></i></div>
                <div>
                  <div class="jd-ov-label">Langue</div>
                  <div class="jd-ov-value"><?= htmlspecialchars($stori_serie_data->language) ?></div>
                </div>
              </div>
              <?php endif; ?>

              <?php if (!empty($stori_serie_data->views)): ?>
              <div class="jd-ov-row">
                <div class="jd-ov-icon"><i class="fa fa-eye"></i></div>
                <div>
                  <div class="jd-ov-label">Vues</div>
                  <div class="jd-ov-value"><?= (int)$stori_serie_data->views ?></div>
                </div>
              </div>
              <?php endif; ?>

            </div>
          </div>
          <!-- /overview -->

        </div>
        <!-- /Right column -->

      </div><!-- /.row -->
    </div><!-- /.container -->
  </section>

  <footer class="footer-style-two" style="background-image:url(assets/images/background/10.jpg)">
    <?php include("includes/footer.php") ?>
  </footer>
</div>

<div class="scroll-to-top scroll-to-target" data-target="html"><span class="fa fa-arrow-up"></span></div>
<?php include("includes/script.php") ?>
</body>
</html>