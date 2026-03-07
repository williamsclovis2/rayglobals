<?php
/**
 * blog-item.php — Single job card include.
 * Used inside the foreach loop in vacancies.php.
 * Matches the professional card design of the new vacancies page.
 */

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
    'Chauffeur'    => 'Chauffeur',
    'construction' => 'Construction / BTP',
    'agriculture'  => 'Agriculture',
    'autre'        => 'Autre',
];

$_jt    = $jobTypeLabels[$stori_serie_data->job_type ?? ''] ?? ($stori_serie_data->job_type ?? '');
$_cat   = $categoryLabels[$stori_serie_data->category ?? ''] ?? ($stori_serie_data->category ?? '');
$_isNew = !empty($stori_serie_data->posting_date)
          && strtotime($stori_serie_data->posting_date) >= strtotime('-3 days');
$_link  = DN . '/vacancies/' . Hash::encryptToken($stori_serie_data->ID);
$_init  = strtoupper(substr($stori_serie_data->company_name ?? 'J', 0, 1));
?>

<div class="job-card">

  <!-- Logo -->
  <?php if (!empty($stori_serie_data->photo)): ?>
    <img class="job-logo"
         src="<?= DNADMIN . '/' . htmlspecialchars($stori_serie_data->photo) ?>"
         alt="<?= htmlspecialchars($stori_serie_data->company_name ?? '') ?>"
         onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
    <div class="job-logo-ph" style="display:none"><?= $_init ?></div>
  <?php else: ?>
    <div class="job-logo-ph"><?= $_init ?></div>
  <?php endif; ?>

  <!-- Body -->
  <div class="job-body">

    <h3 class="job-title">
      <a href="<?= $_link ?>"><?= htmlspecialchars($stori_serie_data->serie_title ?? '') ?></a>
    </h3>

    <div class="job-company">
      <i class="fa fa-building"></i>
      <?= htmlspecialchars($stori_serie_data->company_name ?? '') ?>
    </div>

    <div class="job-meta">

      <?php if (!empty($_jt)): ?>
        <span class="jbadge jb-type">
          <i class="fa fa-briefcase"></i><?= htmlspecialchars($_jt) ?>
        </span>
      <?php endif; ?>

      <?php if (!empty($stori_serie_data->location)): ?>
        <span class="jbadge jb-loc">
          <i class="fa fa-map-marker-alt"></i><?= htmlspecialchars($stori_serie_data->location) ?>
        </span>
      <?php endif; ?>

      <?php if (!empty($stori_serie_data->experience_range)): ?>
        <span class="jbadge jb-exp">
          <i class="fa fa-chart-line"></i><?= htmlspecialchars($stori_serie_data->experience_range) ?> ans
        </span>
      <?php endif; ?>

      <?php if (!empty($_cat)): ?>
        <span class="jbadge jb-cat"><?= htmlspecialchars($_cat) ?></span>
      <?php endif; ?>

      <?php if ($_isNew): ?>
        <span class="jbadge jb-new">✦ Nouveau</span>
      <?php endif; ?>

    </div>

    <?php if (!empty($stori_serie_data->serie_description)): ?>
      <p class="job-desc"><?= htmlspecialchars($stori_serie_data->serie_description) ?></p>
    <?php endif; ?>

  </div>
  <!-- /body -->

  <!-- Right -->
  <div class="job-right">
    <?php if (!empty($stori_serie_data->posting_date)): ?>
      <span class="job-date">
        <i class="fa fa-calendar-alt"></i>
        <?= date('d M Y', strtotime($stori_serie_data->posting_date)) ?>
      </span>
    <?php endif; ?>
    <a href="<?= $_link ?>" class="job-btn">
      Voir l'offre <i class="fa fa-arrow-right"></i>
    </a>
  </div>
  <!-- /right -->

</div>