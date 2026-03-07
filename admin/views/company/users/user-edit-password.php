

<!-- ── Page Header ── -->
<div class="uf-page-header">
  <h1>Changer le <span>mot de passe</span></h1>
  <div>
    <a href="<?= DNADMIN ?>/company/users/list" class="uf-btn-back">
      <i class="fa fa-arrow-left"></i> Liste des utilisateurs
    </a>
  </div>
</div>

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
          <?= htmlspecialchars(
            trim(
              ($form->ERRORS_STRING ?? '') . ' ' .
              (@$form->ERRORS_SCRIPT['password'][0] ?? '') . ' ' .
              (@$form->ERRORS_SCRIPT['repassword'][0] ?? '')
            )
          ) ?>
        </div>
      <?php endif; ?>

      <form method="post" id="uf-pw-form">

        <!-- Hidden fields — original logic preserved exactly -->
        <input type="hidden" name="request"  value="user-update">
        <input type="hidden" name="webToken" value="56">
        <input type="hidden" name="id"       value="<?= $user_data->ID ?>">
        <input type="hidden" name="token"    value="true">
        <input type="hidden" name="task"     value="user-update">

        <div class="uf-card">

          <div class="uf-card-header">
            <div class="uf-card-header-icon"><i class="fa fa-lock"></i></div>
            <div>
              <h2>Changer le mot de passe</h2>
              <p>Majuscules, minuscules, chiffres et caractères spéciaux recommandés</p>
            </div>
          </div>

          <div class="uf-card-body">

            <!-- ── Security ── -->
            <div class="uf-divider"><span>Nouveau mot de passe</span></div>

            <div class="uf-field-group">
              <label class="uf-label" for="u_new_password">
                Nouveau mot de passe <span class="req">*</span>
                <?php if (isset($form) && $form->ERRORS && @$form->ERRORS_SCRIPT['password'][0]): ?>
                  <span class="uf-err"><?= htmlspecialchars($form->ERRORS_SCRIPT['password'][0]) ?></span>
                <?php endif; ?>
              </label>
              <div class="uf-pw-wrap">
                <input type="password" class="uf-input passwordField" name="user-password"
                       id="u_new_password" placeholder="Nouveau mot de passe">
                <button type="button" class="uf-pw-eye" onclick="ufTogglePw(this)" tabindex="-1">
                  <i class="fa fa-eye"></i>
                </button>
              </div>
            </div>

            <div class="uf-field-group">
              <label class="uf-label" for="u_repassword">
                Confirmer le mot de passe <span class="req">*</span>
                <?php if (isset($form) && $form->ERRORS && @$form->ERRORS_SCRIPT['repassword'][0]): ?>
                  <span class="uf-err"><?= htmlspecialchars($form->ERRORS_SCRIPT['repassword'][0]) ?></span>
                <?php endif; ?>
              </label>
              <div class="uf-pw-wrap">
                <input type="password" class="uf-input passwordField" name="user-repassword"
                       id="u_repassword" placeholder="Retaper le mot de passe">
                <button type="button" class="uf-pw-eye" onclick="ufTogglePw(this)" tabindex="-1">
                  <i class="fa fa-eye"></i>
                </button>
              </div>
            </div>

          </div><!-- /.uf-card-body -->

          <div class="uf-form-actions">
            <a href="<?= DNADMIN ?>/company/users/list" class="uf-btn uf-btn-outline">
              <i class="fa fa-times uf-icon"></i> Annuler
            </a>
            <button type="submit" class="uf-btn uf-btn-primary" id="uf-pw-submit-btn">
              <span class="uf-spinner"></span>
              <i class="fa fa-save uf-icon"></i>
              Enregistrer le mot de passe
            </button>
          </div>

        </div><!-- /.uf-card -->

      </form>
    </div><!-- /.col-md-8 -->
  </div><!-- /.row -->
</div><!-- /.container-fluid -->

<script>
/* Toggle all password fields visibility — original pwdvisibility logic preserved */
function ufTogglePw(btn) {
  var fields = document.querySelectorAll('.passwordField');
  var isPassword = fields[0].type === 'password';
  fields.forEach(function(f) { f.type = isPassword ? 'text' : 'password'; });
  document.querySelectorAll('.uf-pw-eye i').forEach(function(icon) {
    icon.className = isPassword ? 'fa fa-eye-slash' : 'fa fa-eye';
  });
}

/* Submit loading state */
var is_safari = navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1;
if (is_safari) {
  document.getElementById('uf-pw-submit-btn').addEventListener('click', function() {
    this.querySelector('.uf-spinner').style.display = 'inline-block';
    this.querySelector('.uf-icon').style.display = 'none';
  });
} else {
  document.getElementById('uf-pw-form').addEventListener('submit', function() {
    var btn = document.getElementById('uf-pw-submit-btn');
    btn.classList.add('loading');
    btn.setAttribute('disabled', 'disabled');
  });
}
</script>