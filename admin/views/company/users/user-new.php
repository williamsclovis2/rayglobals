<?php
/**
 * user_new.php
 * New user form — same design system as vacancy forms, single card, no steps
 */
?>
<style>
  

  body {
    font-family: 'DM Sans', sans-serif;
    background: var(--surface2);
    color: var(--text);
    font-size: 14px;
    line-height: 1.6;
  }

  
</style>

<!-- ── Page Header ── -->

<div class="page-header">
  <h1>Nouvel <span>utilisateur</span></h1>
 
  <div class="header-actions">
    <a href="<?= DNADMIN ?>/company/users/list" class="uf-btn-back">
      <i class="fa fa-arrow-left"></i> Liste des utilisateurs
    </a>
  </div>
</div>

<!-- ══════════════════════════════════════════════════
     Bootstrap layout — form col-md-8 left-aligned
     ══════════════════════════════════════════════════ -->
<div class="container-fluid" style="padding: 28px 20px 80px; max-width: 1140px;">
  <div class="row">
    <div class="col-md-8">

      <?php if (!empty($_SESSION['success'])): ?>
        <div class="uf-alert uf-alert-success">
          <i class="fa fa-check-circle"></i>
          <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
        </div>
      <?php endif; ?>

      <?php if (isset($form) && $form->ERRORS): ?>
        <div class="uf-alert uf-alert-error">
          <i class="fa fa-exclamation-circle"></i>
          Veuillez corriger les erreurs ci-dessous avant de continuer.
        </div>
      <?php endif; ?>

      <form method="post" id="uf-new-form">

        <!-- Hidden fields — original logic preserved -->
        <input type="hidden" name="request"           value="user-new">
        <input type="hidden" name="register_submited" value="1">
        <input type="hidden" name="webToken"          value="56">

        <div class="uf-card">

          <div class="uf-card-header">
            <div class="uf-card-header-icon"><i class="fa fa-user-plus"></i></div>
            <div>
              <h2>Créer un nouvel utilisateur</h2>
              <p>Remplissez les informations du compte</p>
            </div>
          </div>

          <div class="uf-card-body">

            <!-- ── Identity ── -->
            <div class="uf-divider"><span>Identité</span></div>

            <div class="row">
              <div class="col-sm-6">
                <div class="uf-field-group">
                  <label class="uf-label" for="u_firstname">
                    Prénom <span class="req">*</span>
                    <?php if (isset($form) && $form->ERRORS && @$form->ERRORS_SCRIPT['firstname'][0]): ?>
                      <span class="uf-err"><?= htmlspecialchars($form->ERRORS_SCRIPT['firstname'][0]) ?></span>
                    <?php endif; ?>
                  </label>
                  <input type="text" class="uf-input" name="user-firstname" id="u_firstname"
                         placeholder="Prénom" maxlength="50"
                         value="<?= (isset($form) && $form->ERRORS) ? htmlspecialchars(@$_POST['user-firstname']) : '' ?>">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="uf-field-group">
                  <label class="uf-label" for="u_lastname">
                    Nom <span class="req">*</span>
                    <?php if (isset($form) && $form->ERRORS && @$form->ERRORS_SCRIPT['lastname'][0]): ?>
                      <span class="uf-err"><?= htmlspecialchars($form->ERRORS_SCRIPT['lastname'][0]) ?></span>
                    <?php endif; ?>
                  </label>
                  <input type="text" class="uf-input" name="user-lastname" id="u_lastname"
                         placeholder="Nom de famille" maxlength="50"
                         value="<?= (isset($form) && $form->ERRORS) ? htmlspecialchars(@$_POST['user-lastname']) : '' ?>">
                </div>
              </div>
            </div>

            <div class="uf-field-group">
              <label class="uf-label" for="u_email">
                Email <span class="req">*</span>
                <?php if (isset($form) && $form->ERRORS && @$form->ERRORS_SCRIPT['email'][0]): ?>
                  <span class="uf-err"><?= htmlspecialchars($form->ERRORS_SCRIPT['email'][0]) ?></span>
                <?php endif; ?>
              </label>
              <input type="email" class="uf-input" name="user-email" id="u_email"
                     placeholder="exemple@domaine.com" maxlength="100"
                     value="<?= (isset($form) && $form->ERRORS) ? htmlspecialchars(@$_POST['user-email']) : '' ?>">
            </div>

            <div class="uf-field-group">
              <label class="uf-label" for="u_telephone">
                Téléphone
                <?php if (isset($form) && $form->ERRORS && @$form->ERRORS_SCRIPT['telephone'][0]): ?>
                  <span class="uf-err"><?= htmlspecialchars($form->ERRORS_SCRIPT['telephone'][0]) ?></span>
                <?php endif; ?>
              </label>
              <input type="text" class="uf-input" name="user-telephone" id="u_telephone"
                     placeholder="+250 7XX XXX XXX" maxlength="20"
                     value="<?= (isset($form) && $form->ERRORS) ? htmlspecialchars(@$_POST['user-telephone']) : '' ?>">
            </div>

            <!-- ── Security ── -->
            <div class="uf-divider"><span>Sécurité</span></div>

            <div class="uf-field-group">
              <label class="uf-label" for="u_password">
                Mot de passe <span class="req">*</span>
                <?php if (isset($form) && $form->ERRORS && @$form->ERRORS_SCRIPT['password'][0]): ?>
                  <span class="uf-err"><?= htmlspecialchars($form->ERRORS_SCRIPT['password'][0]) ?></span>
                <?php endif; ?>
              </label>
              <div class="uf-pw-wrap">
                <input type="password" class="uf-input" name="user-password" id="u_password"
                       placeholder="Mot de passe">
                <button type="button" class="uf-pw-eye" onclick="ufTogglePw('u_password', this)" tabindex="-1">
                  <i class="fa fa-eye"></i>
                </button>
              </div>
            </div>

            <div class="uf-field-group">
              <label class="uf-label" for="u_retype_password">
                Confirmer le mot de passe <span class="req">*</span>
                <?php if (isset($form) && $form->ERRORS && @$form->ERRORS_SCRIPT['retype_password'][0]): ?>
                  <span class="uf-err"><?= htmlspecialchars($form->ERRORS_SCRIPT['retype_password'][0]) ?></span>
                <?php endif; ?>
              </label>
              <div class="uf-pw-wrap">
                <input type="password" class="uf-input" name="user-retype_password" id="u_retype_password"
                       placeholder="Retaper le mot de passe">
                <button type="button" class="uf-pw-eye" onclick="ufTogglePw('u_retype_password', this)" tabindex="-1">
                  <i class="fa fa-eye"></i>
                </button>
              </div>
            </div>

            <!-- ── Permission ── -->
            <div class="uf-divider"><span>Permission</span></div>

            <div class="uf-field-group">
              <label class="uf-label" for="u_groups">
                Rôle <span class="req">*</span>
                <?php if (isset($form) && $form->ERRORS && @$form->ERRORS_SCRIPT['groups'][0]): ?>
                  <span class="uf-err"><?= htmlspecialchars($form->ERRORS_SCRIPT['groups'][0]) ?></span>
                <?php endif; ?>
              </label>
              <select class="uf-select" name="user-groups" id="u_groups">
                <option value="">— Sélectionnez un rôle —</option>
                <option value="Admin"      <?= (isset($form) && $form->ERRORS && @$_POST['user-groups'] == 'Admin')      ? 'selected' : '' ?>>Admin</option>
                <option value="ContentMan" <?= (isset($form) && $form->ERRORS && @$_POST['user-groups'] == 'ContentMan') ? 'selected' : '' ?>>Content Manager</option>
              </select>
            </div>

          </div><!-- /.uf-card-body -->

          <div class="uf-form-actions">
            <a href="<?= DNADMIN ?>/company/users/list" class="uf-btn uf-btn-outline">
              <i class="fa fa-times uf-icon"></i> Annuler
            </a>
            <button type="submit" class="uf-btn uf-btn-primary" id="uf-submit-btn">
              <span class="uf-spinner"></span>
              <i class="fa fa-user-plus uf-icon"></i>
              Créer l'utilisateur
            </button>
          </div>

        </div><!-- /.uf-card -->

      </form>
    </div><!-- /.col-md-8 -->
  </div><!-- /.row -->
</div><!-- /.container-fluid -->

<script>
/* Password eye toggle */
function ufTogglePw(id, btn) {
  var input = document.getElementById(id);
  var isText = input.type === 'text';
  input.type = isText ? 'password' : 'text';
  btn.querySelector('i').className = isText ? 'fa fa-eye' : 'fa fa-eye-slash';
}

/* Submit loading state — original browser-detect logic preserved */
var is_safari = navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1;
if (is_safari) {
  document.getElementById('uf-submit-btn').addEventListener('click', function() {
    this.querySelector('.uf-spinner').style.display = 'inline-block';
    this.querySelector('.uf-icon').style.display = 'none';
  });
} else {
  document.getElementById('uf-new-form').addEventListener('submit', function() {
    var btn = document.getElementById('uf-submit-btn');
    btn.classList.add('loading');
    btn.setAttribute('disabled', 'disabled');
  });
}
</script>