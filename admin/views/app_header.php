<?php
/**
 * header.php
 * Redesigned top header — same design system as vacancy/user pages
 * No CSS framework conflicts — uses .rg-header-* prefix
 * Original PHP logic (session user, dropdown, profile/logout links) fully preserved
 */
?>

<style>

</style>

<header class="rg-header" id="rg-header">

  <!-- Left -->
  <div class="rg-header-left">
    <!-- Sidebar toggle — preserves Bootstrap offcanvas data attr -->
    <button class="rg-sidebar-toggle" data-toggle="offcanvas" aria-label="Toggle navigation">
      <i class="fa fa-bars"></i>
    </button>

    <!-- Breadcrumb placeholder — adapt per page -->
    <div class="rg-breadcrumb">
      <a href="<?= DNADMIN ?>"><i class="fa fa-home"></i></a>
      <span class="sep"><i class="fa fa-chevron-right"></i></span>
      <span class="cur">Tableau de bord</span>
    </div>
  </div>

  <!-- Right -->
  <div class="rg-header-right">

    <!-- User dropdown — original PHP logic preserved -->
    <div class="rg-user-dropdown" id="rg-user-dd">
      <button class="rg-user-trigger" onclick="rgDdToggle()" type="button">
        <?php if (file_exists(bk_dir($session_user_data->profile))): ?>
          <img src="<?= DNADMIN . _ . $session_user_data->profile ?>"
               class="rg-header-avatar" alt="Avatar">
        <?php else: ?>
          <div class="rg-header-avatar-initials">
            <?= strtoupper(substr($session_user_data->firstname, 0, 1)) ?>
          </div>
        <?php endif; ?>
        <span class="rg-trigger-name"><?= htmlspecialchars($session_user_data->firstname) ?></span>
        <i class="fa fa-chevron-down rg-trigger-arrow"></i>
      </button>

      <div class="rg-dropdown-panel">

        <!-- Header — original user info display -->
        <div class="rg-dropdown-header">
          <?php if (file_exists(bk_dir($session_user_data->profile))): ?>
            <img src="<?= DNADMIN . _ . $session_user_data->profile ?>"
                 class="rg-dropdown-avatar-lg" alt="Avatar">
          <?php else: ?>
            <div class="rg-dropdown-avatar-lg-initials">
              <?= strtoupper(substr($session_user_data->firstname, 0, 1)) ?>
            </div>
          <?php endif; ?>
          <div class="rg-dropdown-info">
            <div class="rg-dropdown-name"><?= htmlspecialchars($session_user_data->firstname) ?></div>
            <div class="rg-dropdown-role"><?= htmlspecialchars($session_user_data->groups) ?></div>
          </div>
        </div>

        <!-- Links — original hrefs preserved -->
        <div class="rg-dropdown-links">
          <a href="<?= DNADMIN ?>/company/users/<?= $session_user_data->ID ?>/edit"
             class="rg-dropdown-link">
            <i class="fa fa-user"></i> Mon profil
          </a>
          <a href="<?= DNADMIN ?>/company/users/<?= $session_user_data->ID ?>/edit-password"
             class="rg-dropdown-link">
            <i class="fa fa-lock"></i> Changer le mot de passe
          </a>
          <div class="rg-dropdown-divider"></div>
          <a href="<?= DNADMIN ?>/logout" class="rg-dropdown-link rg-signout">
            <i class="fa fa-sign-out"></i> Déconnexion
          </a>
        </div>

      </div><!-- /.rg-dropdown-panel -->
    </div><!-- /.rg-user-dropdown -->

  </div><!-- /.rg-header-right -->

</header>

<script>
/* User dropdown toggle */
function rgDdToggle() {
  document.getElementById('rg-user-dd').classList.toggle('rg-open');
}

/* Close on outside click */
document.addEventListener('click', function(e) {
  var dd = document.getElementById('rg-user-dd');
  if (dd && !dd.contains(e.target)) {
    dd.classList.remove('rg-open');
  }
});
</script>