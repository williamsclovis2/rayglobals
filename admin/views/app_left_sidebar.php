<?php
/**
 * sidebar.php
 * Redesigned sidebar — same design system as vacancy/user pages
 * No CSS framework conflicts — uses .rg-sidebar-* prefix
 * Original PHP logic (url_struc active states, session checks) fully preserved
 */
?>

<style>



</style>

<aside class="rg-sidebar" id="rg-sidebar">

  <!-- Brand -->
  <a href="<?= DNADMIN ?>" class="rg-brand">
    <div class="rg-brand-icon">R</div>
    <div class="rg-brand-name">
      Ray Globals
      <small>Admin Panel</small>
    </div>
  </a>

  <!-- User panel — original PHP logic preserved -->
  <div class="rg-user-panel">
    <?php if (file_exists(DNADMIN . _ . $session_user_data->profile)): ?>
      <img src="<?= DNADMIN . _ . $session_user_data->profile ?>"
           class="rg-user-avatar" alt="Avatar">
    <?php else: ?>
      <div class="rg-user-avatar-initials">
        <?= strtoupper(substr($session_user_data->firstname, 0, 1)) ?>
      </div>
    <?php endif; ?>
    <div class="rg-user-info">
      <div class="rg-user-name"><?= htmlspecialchars($session_user_data->firstname) ?></div>
      <div class="rg-user-status">
        <i class="fa fa-circle"></i> En ligne
      </div>
    </div>
  </div>

  <!-- Nav -->
  <nav class="rg-nav">
    <ul style="list-style:none;padding:0;margin:0">

      <!-- ── OFFRES ── -->
      <li class="rg-nav-label">Offres</li>

      <li class="rg-nav-item <?= ($url_struc['tree'] == 'app' && $url_struc['app-idname'] == 'EventApp') ? 'rg-active rg-open' : '' ?>">
        <a class="rg-nav-link" onclick="rgToggle(this)">
          <i class="fa fa-briefcase rg-icon"></i>
          <span>Offres d'emploi</span>
          <i class="fa fa-chevron-right rg-arrow"></i>
        </a>
        <ul class="rg-submenu">
          <li>
            <a href="<?= DNADMIN ?>/app/emploi/list"
               class="rg-sub-link <?= ($url_struc['tree'] == 'app' && $url_struc['trunk'] == 'home') ? 'rg-sub-active' : '' ?>">
              <i class="fa fa-circle"></i> Liste des offres
            </a>
          </li>
        </ul>
      </li>

	  
     

	  <!-- ── OFFRES ── -->
      <li class="rg-nav-label">Applications</li>

      <li class="rg-nav-item <?= ($url_struc['tree'] == 'app' && $url_struc['app-idname'] == 'EventApp') ? 'rg-active rg-open' : '' ?>">
        <a class="rg-nav-link" onclick="rgToggle(this)">
          <i class="fa fa-briefcase rg-icon"></i>
          <span>List d'applications</span>
          <i class="fa fa-chevron-right rg-arrow"></i>
        </a>
        <ul class="rg-submenu">
          <li>
            <a href="<?= DNADMIN ?>/app/emploi/candidatures"
               class="rg-sub-link <?= ($url_struc['tree'] == 'app' && $url_struc['trunk'] == 'home') ? 'rg-sub-active' : '' ?>">
              <i class="fa fa-circle"></i> Liste des candidatures
            </a>
          </li>
        </ul>
      </li>

      <!-- ── COMPANY ── -->
      <li class="rg-nav-label">Company</li>

      <li class="rg-nav-item <?= ($url_struc['tree'] == 'company' && $url_struc['trunk'] == 'users') ? 'rg-active rg-open' : '' ?>">
        <a class="rg-nav-link" onclick="rgToggle(this)">
          <i class="fa fa-users rg-icon"></i>
          <span>Comptes</span>
          <i class="fa fa-chevron-right rg-arrow"></i>
        </a>
        <ul class="rg-submenu">
          <li>
            <a href="<?= DNADMIN ?>/company/users/list"
               class="rg-sub-link <?= ($url_struc['tree'] == 'company' && $url_struc['trunk'] == 'users') ? 'rg-sub-active' : '' ?>">
              <i class="fa fa-circle"></i> Utilisateurs
            </a>
          </li>
        </ul>
      </li>

      <!-- ── REPORTS (hidden — preserved as-is) ── -->
      <li class="rg-nav-label rg-hidden">Reports</li>

      <li class="rg-nav-item rg-hidden <?= ($url_struc['tree'] == 'app' && $url_struc['app-idname'] == 'EventApp') ? 'rg-active rg-open' : '' ?>">
        <a class="rg-nav-link" onclick="rgToggle(this)">
          <i class="fa fa-book rg-icon"></i>
          <span>Subscriptions</span>
          <i class="fa fa-chevron-right rg-arrow"></i>
        </a>
        <ul class="rg-submenu">
          <li>
            <a href="<?= DNADMIN ?>/app/report/customersubscriptions" class="rg-sub-link">
              <i class="fa fa-circle"></i> Customer subscriptions
            </a>
          </li>
          <li>
            <a href="<?= DNADMIN ?>/app/report/salesrevenuebyserie" class="rg-sub-link">
              <i class="fa fa-circle"></i> Revenues by serie
            </a>
          </li>
          <li>
            <a href="<?= DNADMIN ?>/app/report/customersubscriptionslog" class="rg-sub-link">
              <i class="fa fa-circle"></i> Subscriptions log
            </a>
          </li>
        </ul>
      </li>

      <!-- ── Logout ── -->
      <li class="rg-nav-item rg-logout" style="margin-top: 16px;">
        <a href="<?= DNADMIN ?>/logout" class="rg-nav-link">
          <i class="fa fa-sign-out rg-icon"></i>
          <span>Déconnexion</span>
        </a>
      </li>

    </ul>
  </nav>

</aside>

<script>
/* Accordion toggle for treeview items */
function rgToggle(link) {
  var item = link.parentElement;
  var isOpen = item.classList.contains('rg-open');

  /* Close all siblings */
  var siblings = item.parentElement.querySelectorAll('.rg-nav-item.rg-open');
  siblings.forEach(function(s) {
    if (s !== item) s.classList.remove('rg-open');
  });

  item.classList.toggle('rg-open', !isOpen);
}

/* Auto-open active parent on load */
document.querySelectorAll('.rg-nav-item.rg-active').forEach(function(item) {
  item.classList.add('rg-open');
});
</script>