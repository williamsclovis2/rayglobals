<?php
/**
 * vacancies.php — Professional job listing page with working sidebar filters.
 */

$page = "vacancies";
require_once "admin/core/init.php";
$_display = Input::get('page', 'get');

$jobTypeOptions = [
    'tempsplein'    => 'Temps plein',
    'tempspartiel'  => 'Temps partiel',
    'roster'        => 'Roster / Consultant',
    'stage'         => 'Stage',
    'interim'       => 'Intérim',
    'apprentissage' => 'Apprentissage',
];
$categoryOptions = [
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
$expRangeOptions  = ['1-2', '2-3', '3-6', '6+'];
$postedWithinOpts = [1 => "Aujourd'hui", 2 => '2 derniers jours', 3 => '3 derniers jours', 5 => '5 derniers jours', 10 => '10 derniers jours'];

/* Sanitise GET */
$f_jobTypes     = array_filter((array)($_GET['job_type'] ?? []), fn($v) => array_key_exists($v, $jobTypeOptions));
$f_category     = array_key_exists($_GET['category'] ?? '', $categoryOptions) ? $_GET['category'] : '';
$f_location     = trim(strip_tags($_GET['location'] ?? ''));
$f_expRanges    = array_filter((array)($_GET['experience_range'] ?? []), fn($v) => in_array($v, $expRangeOptions, true));
$f_postedWithin = isset($_GET['posted_within']) && array_key_exists((int)$_GET['posted_within'], $postedWithinOpts) ? (int)$_GET['posted_within'] : 0;
$hasFilters     = $f_category || $f_location || $f_jobTypes || $f_expRanges || $f_postedWithin;

/* Build SQL */
$where  = ["state = 'Published'"]; $params = [];
if ($f_jobTypes) { $ph = implode(',', array_fill(0, count($f_jobTypes), '?')); $where[] = "job_type IN ($ph)"; $params = array_merge($params, array_values($f_jobTypes)); }
if ($f_category) { $where[] = 'category = ?'; $params[] = $f_category; }
if ($f_location !== '') { $where[] = 'location LIKE ?'; $params[] = '%' . $f_location . '%'; }
if ($f_expRanges) { $ph = implode(',', array_fill(0, count($f_expRanges), '?')); $where[] = "experience_range IN ($ph)"; $params = array_merge($params, array_values($f_expRanges)); }
if ($f_postedWithin > 0) { $where[] = 'posting_date >= DATE_SUB(CURDATE(), INTERVAL ? DAY)'; $params[] = $f_postedWithin; }
$sql = 'SELECT * FROM `stori_serie` WHERE ' . implode(' AND ', $where) . ' ORDER BY pin_top DESC';
$storiSerieTable = new StoriSerie();
$storiSerieTable->selectQuery($sql, $params);
$totalCount = $storiSerieTable->count();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <?php include("includes/head.php") ?>
  <link rel="stylesheet" href="<?= DN ?>/assets/css/vacancies-new.css">


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

  <section class="page-title">
    <div class="content" style="background-image:url(assets/images/resource/hiring.jpg)">
      <div class="auto-container"><h1>Nos offres d'emploi</h1></div>
    </div>
   
  </section>

  <!-- Top search bar -->
  <div class="vac-search-bar">
    <div class="container">
      <form method="GET" action="" class="vac-search-inner">
        <input type="text" name="location"
               value="<?= htmlspecialchars($f_location) ?>"
               placeholder="🔍  Ville, région, mot-clé…">
        <select name="category">
          <option value="">Toutes catégories</option>
          <?php foreach ($categoryOptions as $val => $lbl): ?>
            <option value="<?= $val ?>" <?= $f_category === $val ? 'selected' : '' ?>><?= htmlspecialchars($lbl) ?></option>
          <?php endforeach; ?>
        </select>
        <select name="experience_range[]">
          <option value="">Expérience</option>
          <?php foreach ($expRangeOptions as $val): ?>
            <option value="<?= $val ?>" <?= in_array($val, $f_expRanges, true) ? 'selected' : '' ?>>
              <?= $val === '6+' ? '+6 ans' : $val . ' ans' ?>
            </option>
          <?php endforeach; ?>
        </select>
        <button type="submit" class="vac-search-btn"><i class="fa fa-search"></i> Rechercher</button>
      </form>
    </div>
  </div>

  <!-- Main -->
  <section class="vac-section">
    <div class="container">
      <div class="row">

        <!-- SIDEBAR -->
        <div class="col-xl-3 col-lg-3 col-md-4">
          <div class="vac-sidebar">
            <form method="GET" action="" id="filter-form">

              <!-- Catégorie -->
              <div class="filter-card">
                <div class="filter-card-hd">
                  <h5><i class="fa fa-th-large"></i> Catégorie</h5>
                </div>
                <div class="filter-card-body">
                  <select name="category" class="filter-select" onchange="this.form.submit()">
                    <option value="">Toutes les catégories</option>
                    <?php foreach ($categoryOptions as $val => $lbl): ?>
                      <option value="<?= $val ?>" <?= $f_category === $val ? 'selected' : '' ?>><?= htmlspecialchars($lbl) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <!-- Type d'emploi -->
              <div class="filter-card">
                <div class="filter-card-hd">
                  <h5><i class="fa fa-briefcase"></i> Type d'emploi</h5>
                </div>
                <div class="filter-card-body">
                  <?php foreach ($jobTypeOptions as $val => $lbl): ?>
                    <div class="fo">
                      <input type="checkbox" id="jt_<?= $val ?>" name="job_type[]"
                             value="<?= $val ?>" <?= in_array($val, $f_jobTypes, true) ? 'checked' : '' ?>
                             onchange="this.form.submit()">
                      <label for="jt_<?= $val ?>"><?= htmlspecialchars($lbl) ?></label>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>

              <!-- Localisation -->
              <div class="filter-card">
                <div class="filter-card-hd">
                  <h5><i class="fa fa-map-marker-alt"></i> Localisation</h5>
                </div>
                <div class="filter-card-body">
                  <div class="filter-irow">
                    <input type="text" name="location" class="filter-input"
                           value="<?= htmlspecialchars($f_location) ?>" placeholder="Ville, région…">
                    <button type="submit" class="filter-ibtn"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </div>

              <!-- Expérience -->
              <div class="filter-card">
                <div class="filter-card-hd">
                  <h5><i class="fa fa-chart-line"></i> Expérience</h5>
                </div>
                <div class="filter-card-body">
                  <?php foreach ($expRangeOptions as $val): ?>
                    <div class="fo">
                      <input type="checkbox" id="er_<?= str_replace('+','p',$val) ?>"
                             name="experience_range[]" value="<?= $val ?>"
                             <?= in_array($val, $f_expRanges, true) ? 'checked' : '' ?>
                             onchange="this.form.submit()">
                      <label for="er_<?= str_replace('+','p',$val) ?>">
                        <?= $val === '6+' ? 'Plus de 6 ans' : $val . ' ans' ?>
                      </label>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>

              <!-- Publié dans -->
              <div class="filter-card">
                <div class="filter-card-hd">
                  <h5><i class="fa fa-calendar-alt"></i> Publié dans</h5>
                </div>
                <div class="filter-card-body">
                  <div class="fo">
                    <input type="radio" id="pw0" name="posted_within" value="0"
                           <?= $f_postedWithin === 0 ? 'checked' : '' ?> onchange="this.form.submit()">
                    <label for="pw0">N'importe quand</label>
                  </div>
                  <?php foreach ($postedWithinOpts as $days => $lbl): ?>
                    <div class="fo">
                      <input type="radio" id="pw<?= $days ?>" name="posted_within" value="<?= $days ?>"
                             <?= $f_postedWithin === $days ? 'checked' : '' ?> onchange="this.form.submit()">
                      <label for="pw<?= $days ?>"><?= htmlspecialchars($lbl) ?></label>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>

              <?php if ($hasFilters): ?>
                <a href="?" class="filter-reset"><i class="fa fa-times"></i> Réinitialiser les filtres</a>
              <?php endif; ?>

            </form>
          </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="col-xl-9 col-lg-9 col-md-8">

          <!-- Results + sort -->
          <div class="vac-results-bar">
            <div class="vac-count">
              <em><?= $totalCount ?></em>
              offre<?= $totalCount !== 1 ? 's' : '' ?> trouvée<?= $totalCount !== 1 ? 's' : '' ?>
              <?php if ($hasFilters): ?>
                <small style="font-size:13px;color:var(--muted);font-weight:400;font-family:'DM Sans',sans-serif"> · filtres actifs</small>
              <?php endif; ?>
            </div>
            <div class="vac-sort">
              Trier par
              <select><option>Plus récent</option><option>Plus populaire</option></select>
            </div>
          </div>

          <!-- Active chips -->
          <?php if ($hasFilters): ?>
          <div class="vac-chips">
            <?php if ($f_category): ?>
              <span class="vac-chip">
                <i class="fa fa-tag"></i><?= htmlspecialchars($categoryOptions[$f_category]) ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['category' => ''])) ?>">×</a>
              </span>
            <?php endif; ?>
            <?php if ($f_location): ?>
              <span class="vac-chip">
                <i class="fa fa-map-marker-alt"></i><?= htmlspecialchars($f_location) ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['location' => ''])) ?>">×</a>
              </span>
            <?php endif; ?>
            <?php foreach ($f_jobTypes as $jt): ?>
              <span class="vac-chip"><i class="fa fa-briefcase"></i><?= htmlspecialchars($jobTypeOptions[$jt]) ?></span>
            <?php endforeach; ?>
            <?php foreach ($f_expRanges as $er): ?>
              <span class="vac-chip"><i class="fa fa-chart-line"></i><?= $er === '6+' ? 'Plus de 6 ans' : $er . ' ans' ?></span>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>

          <!-- Detail view -->
          <?php
          if ($_display === 'details') {
              $_ID = Input::get('id', 'get');
              $dt = new StoriSerie();
              if ($dt->find($_ID)) {
                  StoriSerieController::addView($_ID);
                  $stori_serie_data = $dt->data();
                  include 'includes/blog-details.php';
              }
          }
          ?>

          <!-- Cards -->
          <?php if ($totalCount): ?>
            <?php foreach ($storiSerieTable->data() as $stori_serie_data): ?>
              <?php include 'includes/blog-item.php'; ?>
            <?php endforeach; ?>

            <div class="vac-pagi">
              <span>1</span>
              <a href="#">2</a><a href="#">3</a>
              <a href="#"><i class="fa fa-chevron-right"></i></a>
            </div>

          <?php else: ?>
            <div class="vac-empty">
              <div class="vac-empty-ico"><i class="fa fa-search"></i></div>
              <h3>Aucune offre trouvée</h3>
              <p>Modifiez ou réinitialisez vos filtres pour voir plus de résultats.</p>
              <a href="?">Voir toutes les offres</a>
            </div>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </section>

  <!-- Partners -->
  <section class="clients-section-two style-two" id="partners">
    <div class="auto-container">
      <div class="title-box">
        <div class="row clearfix">
          <div class="column col-lg-6 col-md-12 col-sm-12">
            <div class="icon flaticon-email"></div>
            <h2>Nos Meilleurs <br> Partenaires Corporatifs</h2>
          </div>
          <div class="column col-lg-6 col-md-12 col-sm-12">
            <div class="text">Nous collaborons avec le secteur public pour bâtir des communautés prospères.</div>
          </div>
        </div>
      </div>
      <div class="inner-container">
        <div class="sponsors-outer">
          <ul class="sponsors-carousel owl-carousel owl-theme">
            <li class="slide-item"><figure class="image-box"><a href="#"><img src="assets/images/clients/6.png" alt=""></a></figure></li>
            <li class="slide-item"><figure class="image-box"><a href="#"><img src="assets/images/clients/7.png" alt=""></a></figure></li>
            <li class="slide-item"><figure class="image-box"><a href="#"><img src="assets/images/clients/8.png" alt=""></a></figure></li>
            <li class="slide-item"><figure class="image-box"><a href="#"><img src="assets/images/clients/9.png" alt=""></a></figure></li>
            <li class="slide-item"><figure class="image-box"><a href="#"><img src="assets/images/clients/10.png" alt=""></a></figure></li>
          </ul>
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
</body>
</html>