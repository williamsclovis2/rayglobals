

<!-- ── Page Header ── -->
<div class="uf-page-header">
  <h1>Modifier <span>l'utilisateur</span></h1>
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
          Veuillez corriger les erreurs ci-dessous avant de continuer.
        </div>
      <?php endif; ?>

      <form method="post" id="uf-edit-form">

        <!-- Hidden fields — original logic preserved exactly -->
        <input type="hidden" name="request"           value="user-update">
        <input type="hidden" name="webToken"          value="56">
        <input type="hidden" name="id"                value="<?= $user_data->ID ?>">
        <input type="hidden" name="token"             value="true">
        <input type="hidden" name="task"              value="user-update">

        <div class="uf-card">

          <div class="uf-card-header">
            <div class="uf-card-header-icon"><i class="fa fa-edit"></i></div>
            <div>
              <h2>Modifier l'utilisateur</h2>
              <p>Mettre à jour les informations du compte</p>
            </div>
          </div>

          <div class="uf-card-body">

            <!-- ── Identity ── -->
            <div class="uf-divider"><span>Identité</span></div>

            <div class="row">
              <div class="col-sm-6">
                <div class="uf-field-group">
                  <label class="uf-label" for="ue_firstname">
                    Prénom <span class="req">*</span>
                    <?php if (isset($form) && $form->ERRORS && @$form->ERRORS_SCRIPT['firstname'][0]): ?>
                      <span class="uf-err"><?= htmlspecialchars($form->ERRORS_SCRIPT['firstname'][0]) ?></span>
                    <?php endif; ?>
                  </label>
                  <input type="text" class="uf-input" name="user-firstname" id="ue_firstname"
                         placeholder="Prénom" maxlength="50"
                         value="<?= htmlspecialchars($user_data->firstname ?? '') ?>">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="uf-field-group">
                  <label class="uf-label" for="ue_lastname">
                    Nom <span class="req">*</span>
                    <?php if (isset($form) && $form->ERRORS && @$form->ERRORS_SCRIPT['lastname'][0]): ?>
                      <span class="uf-err"><?= htmlspecialchars($form->ERRORS_SCRIPT['lastname'][0]) ?></span>
                    <?php endif; ?>
                  </label>
                  <input type="text" class="uf-input" name="user-lastname" id="ue_lastname"
                         placeholder="Nom de famille" maxlength="50"
                         value="<?= htmlspecialchars($user_data->lastname ?? '') ?>">
                </div>
              </div>
            </div>

            <div class="uf-field-group">
              <label class="uf-label" for="ue_telephone">
                Téléphone
                <?php if (isset($form) && $form->ERRORS && @$form->ERRORS_SCRIPT['telephone'][0]): ?>
                  <span class="uf-err"><?= htmlspecialchars($form->ERRORS_SCRIPT['telephone'][0]) ?></span>
                <?php endif; ?>
              </label>
              <input type="text" class="uf-input" name="user-telephone" id="ue_telephone"
                     placeholder="+250 7XX XXX XXX" maxlength="20"
                     value="<?= htmlspecialchars($user_data->phone ?? '') ?>">
            </div>

            <!-- ── Permission ── -->
            <div class="uf-divider"><span>Permission</span></div>

            <div class="uf-field-group">
              <label class="uf-label" for="ue_groups">
                Rôle <span class="req">*</span>
                <?php if (isset($form) && $form->ERRORS && @$form->ERRORS_SCRIPT['groups'][0]): ?>
                  <span class="uf-err"><?= htmlspecialchars($form->ERRORS_SCRIPT['groups'][0]) ?></span>
                <?php endif; ?>
              </label>
              <select class="uf-select" name="user-groups" id="ue_groups">
                <?php if ($session_user_data->groups == 'Admin'): ?>
                  <option value="<?= htmlspecialchars($user_data->groups) ?>" selected>
                    <?= htmlspecialchars($user_data->groups) ?>
                  </option>
                <?php endif; ?>
                <?php if ($session_user_data->groups == 'ContentMan' || $session_user_data->groups == 'Admin'): ?>
                  <option value="ContentMan" <?= $user_data->groups == 'ContentMan' ? 'selected' : '' ?>>
                    Content Manager
                  </option>
                <?php endif; ?>
                <?php if ($session_user_data->groups == 'Admin'): ?>
                  <option value="Admin" <?= $user_data->groups == 'Admin' ? 'selected' : '' ?>>
                    Admin
                  </option>
                <?php endif; ?>
              </select>
            </div>

          </div><!-- /.uf-card-body -->

          <div class="uf-form-actions">
            <a href="<?= DNADMIN ?>/company/users/list" class="uf-btn uf-btn-outline">
              <i class="fa fa-times uf-icon"></i> Annuler
            </a>
            <button type="submit" class="uf-btn uf-btn-primary" id="ue-submit-btn">
              <span class="uf-spinner"></span>
              <i class="fa fa-save uf-icon"></i>
              Enregistrer les modifications
            </button>
          </div>

        </div><!-- /.uf-card -->

      </form>
    </div><!-- /.col-md-8 -->
  </div><!-- /.row -->
</div><!-- /.container-fluid -->

<script>
var is_safari = navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1;
if (is_safari) {
  document.getElementById('ue-submit-btn').addEventListener('click', function() {
    this.querySelector('.uf-spinner').style.display = 'inline-block';
    this.querySelector('.uf-icon').style.display = 'none';
  });
} else {
  document.getElementById('uf-edit-form').addEventListener('submit', function() {
    var btn = document.getElementById('ue-submit-btn');
    btn.classList.add('loading');
    btn.setAttribute('disabled', 'disabled');
  });
}
</script>