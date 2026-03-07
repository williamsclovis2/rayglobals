<?php


// ── Handle state actions (block / activate) — original logic preserved ──
if (isset($_POST['request']) && $_POST['request'] === 'user-state') {
    // Handled by the existing form POST (block / activate buttons)
    // The original form+popover logic is kept intact below
}

// ── Count stats ──
$userStatsTable = new User();
$userStatsTable->selectQuery(
    "SELECT state, COUNT(*) as cnt FROM `app_users` WHERE `company_ID`=? AND `state`!='Deleted' GROUP BY state",
    [$session_company_ID]
);
$statsByState = ['Activated' => 0, 'Blocked' => 0, 'Pending' => 0];
if ($userStatsTable->count()) {
    foreach ($userStatsTable->data() as $row) {
        $statsByState[$row->state] = $row->cnt;
    }
}
$totalUsers = array_sum($statsByState);

// ── Main query ──
$userTable = new User();
$userTable->selectQuery(
    "SELECT * FROM `app_users` WHERE `company_ID`=? AND `state`!='Deleted'",
    [$session_company_ID]
);
?>


<!-- ── Page Header ── -->


<div class="page-header">
  <h1>Gestion des <span>Utilisateurs</span></h1>
 
  <div class="header-actions">
     <?php if ($session_user_data->groups == 'Admin' || $session_user_data->groups == 'RG-Admin' || $session_user_data->groups == 'RG-SUPER-Admin'): ?>
      <a href="<?= DNADMIN ?>/company/users/new" class="ul-btn-new">
        <i class="fa fa-plus"></i> Nouvel utilisateur
      </a>
    <?php endif; ?>
  </div>
</div>

<div class="ul-container">

  <!-- ── Stats ── -->
  <div class="ul-stats-row">
    <div class="ul-stat-card">
      <div class="ul-stat-icon all"><i class="fa fa-users"></i></div>
      <div>
        <div class="ul-stat-num"><?= $totalUsers ?></div>
        <div class="ul-stat-label">Total utilisateurs</div>
      </div>
    </div>
    <div class="ul-stat-card">
      <div class="ul-stat-icon active"><i class="fa fa-check-circle"></i></div>
      <div>
        <div class="ul-stat-num"><?= $statsByState['Activated'] ?></div>
        <div class="ul-stat-label">Activés</div>
      </div>
    </div>
    <div class="ul-stat-card">
      <div class="ul-stat-icon blocked"><i class="fa fa-ban"></i></div>
      <div>
        <div class="ul-stat-num"><?= $statsByState['Blocked'] ?></div>
        <div class="ul-stat-label">Bloqués</div>
      </div>
    </div>
  </div>

  <!-- ── Main card ── -->
  <div class="ul-card">

    <div class="ul-card-header">
      <div class="ul-card-header-left">
        <div class="ul-card-header-icon"><i class="fa fa-users"></i></div>
        <div>
          <h2>Liste des utilisateurs</h2>
          <p>Gérez les comptes, accès et statuts</p>
        </div>
      </div>
      <span class="ul-result-count"><?= $userTable->count() ?> utilisateur<?= $userTable->count() > 1 ? 's' : '' ?></span>
    </div>

    <div class="ul-table-wrap">
      <table class="ul-table">
        <thead>
          <tr>
            <th style="width:50px">#</th>
            <th>Utilisateur</th>
            <th class="hide-xs">Email</th>
            <th class="hide-xs">Rôle</th>
            <th>Statut</th>
            <th style="width:120px">Actions</th>
          </tr>
        </thead>
        <tbody>

          <?php if (!$userTable->count()): ?>
            <tr>
              <td colspan="6">
                <div class="ul-empty">
                  <div class="icon"><i class="fa fa-users"></i></div>
                  <h3>Aucun utilisateur trouvé</h3>
                  <p>Créez votre premier utilisateur via le bouton en haut à droite.</p>
                </div>
              </td>
            </tr>

          <?php else: ?>
            <?php
              $i = 0;
              foreach ($userTable->data() as $user_data):
                $i++;
                $user_ID = $user_data->ID;
                if ($session_user_data->groups != "Admin" && $session_user_ID != $user_ID && $session_user_data->groups != "RG-Admin" && $session_user_data->groups != "RG-SUPER-Admin") continue;

                // Badge class for state
                $stateClass = match($user_data->state) {
                  'Activated' => 'ul-badge-activated',
                  'Blocked'   => 'ul-badge-blocked',
                  default     => 'ul-badge-pending',
                };
                // Badge class for role
                $roleClass = match($user_data->groups) {
                  'Admin', 'RG-Admin', 'RG-SUPER-Admin' => 'ul-badge-admin',
                  'ContentMan' => 'ul-badge-content',
                  default      => 'ul-badge-default',
                };
                // Display role label
                $roleLabel = $user_data->groups === 'End-User' ? 'Cube-Admin' : $user_data->groups;

                // Avatar initials
                $initials = strtoupper(substr($user_data->firstname ?? '', 0, 1) . substr($user_data->lastname ?? '', 0, 1));
            ?>

            <tr class="<?= $user_data->state === 'Blocked' ? 'ul-blocked' : '' ?>">

              <td style="color:var(--muted);font-size:12px;font-weight:600"><?= $i ?></td>

              <td>
                <div class="ul-user-identity">
                  <div class="ul-avatar"><?= htmlspecialchars($initials) ?></div>
                  <div>
                    <div class="ul-user-name">
                      <?= htmlspecialchars($user_data->firstname . ' ' . $user_data->lastname) ?>
                    </div>
                    <div class="ul-user-email hide-sm">
                      <?= htmlspecialchars($user_data->email) ?>
                    </div>
                  </div>
                </div>
              </td>

              <td class="hide-xs">
                <span style="font-size:12px;color:var(--muted)"><?= htmlspecialchars($user_data->email) ?></span>
              </td>

              <td class="hide-xs">
                <span class="ul-badge <?= $roleClass ?>">
                  <?= htmlspecialchars($roleLabel) ?>
                </span>
              </td>

              <td>
                <span class="ul-badge <?= $stateClass ?>">
                  <?= htmlspecialchars($user_data->state) ?>
                </span>
              </td>

              <td>
                <!-- Trigger modal — replaces original popover -->
                <button type="button"
                        class="ul-btn"
                        data-toggle="modal"
                        data-target="#ulModal<?= $user_ID ?>"
                        style="font-size:12px;padding:5px 12px">
                  <i class="fa fa-ellipsis-h"></i> Actions
                </button>
              </td>

            </tr>

            <!-- ══════════════════════════════════════════════════════
                 BOOTSTRAP MODAL — per user
                 Original PHP logic (block/activate/edit/pw) preserved
                 ══════════════════════════════════════════════════════ -->
            <div class="modal fade ul-modal"
                 id="ulModal<?= $user_ID ?>"
                 tabindex="-1" role="dialog">
              <div class="modal-dialog" role="document" style="max-width:500px;width:95%">
                <div class="modal-content" style="border:none;border-radius:12px;overflow:hidden">

                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title">
                      <div class="ul-avatar" style="width:28px;height:28px;font-size:11px;border-radius:50%;background:#fff;color:#0c2d62;flex-shrink:0">
                        <?= htmlspecialchars($initials) ?>
                      </div>
                      <?= htmlspecialchars($user_data->firstname . ' ' . $user_data->lastname) ?>
                      <span>#<?= $user_ID ?></span>
                    </h4>
                  </div>

                  <div class="modal-body">

                    <div class="ul-modal-grid">
                      <div class="ul-modal-item">
                        <label>Prénom</label>
                        <div class="val"><?= htmlspecialchars($user_data->firstname) ?></div>
                      </div>
                      <div class="ul-modal-item">
                        <label>Nom</label>
                        <div class="val"><?= htmlspecialchars($user_data->lastname) ?></div>
                      </div>
                      <div class="ul-modal-item" style="grid-column:span 2">
                        <label>Email</label>
                        <div class="val"><?= htmlspecialchars($user_data->email) ?></div>
                      </div>
                      <div class="ul-modal-item">
                        <label>Rôle</label>
                        <div class="val">
                          <span class="ul-badge <?= $roleClass ?>"><?= htmlspecialchars($roleLabel) ?></span>
                        </div>
                      </div>
                      <div class="ul-modal-item">
                        <label>Statut</label>
                        <div class="val">
                          <span class="ul-badge <?= $stateClass ?>"><?= htmlspecialchars($user_data->state) ?></span>
                        </div>
                      </div>
                    </div>

                    <!-- Actions — original PHP logic: form with hidden fields + button name = action -->
                    <?php if ($session_user_data->groups == "Admin" || $session_user_ID == $user_ID || $session_user_data->groups == "RG-Admin" || $session_user_data->groups == "RG-SUPER-Admin"): ?>
                    <form method="post">
                      <input type="hidden" name="webToken"  value="56">
                      <input type="hidden" name="request"   value="user-state">
                      <input type="hidden" name="user-id"   value="<?= $user_ID ?>">

                      <div class="ul-modal-actions">
                        <a href="<?= DNADMIN . "/company/users/{$user_ID}/edit" ?>"
                           class="ul-modal-btn ul-modal-btn-edit">
                          <i class="fa fa-pencil"></i> Modifier
                        </a>
                        <a href="<?= DNADMIN . "/company/users/{$user_ID}/edit-password" ?>"
                           class="ul-modal-btn ul-modal-btn-pw">
                          <i class="fa fa-unlock-alt"></i> Changer le mot de passe
                        </a>
                        <?php if ($session_user_data->ID != $user_data->ID): ?>
                          <?php if ($user_data->state != 'Blocked'): ?>
                            <button class="ul-modal-btn ul-modal-btn-block" name="block" type="submit">
                              <i class="fa fa-ban"></i> Bloquer
                            </button>
                          <?php endif; ?>
                          <?php if ($user_data->state != 'Activated'): ?>
                            <button class="ul-modal-btn ul-modal-btn-activate" name="activate" type="submit">
                              <i class="fa fa-check-circle"></i> Activer
                            </button>
                          <?php endif; ?>
                        <?php endif; ?>
                      </div>
                    </form>
                    <?php endif; ?>

                  </div><!-- /.modal-body -->

                  <div class="modal-footer">
                    <button type="button" class="ul-modal-btn ul-modal-btn-pw" data-dismiss="modal">
                      <i class="fa fa-times"></i> Fermer
                    </button>
                  </div>

                </div><!-- /.modal-content -->
              </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <?php endforeach; ?>
          <?php endif; ?>

        </tbody>
      </table>
    </div><!-- /.ul-table-wrap -->

    <!-- Footer — create user shortcut, shown only to admins (original logic) -->
    <?php if ($session_user_data->groups == 'Admin' || $session_user_data->groups == 'RG-SUPER-Admin'): ?>
      <div class="ul-card-footer">
        <a href="<?= DNADMIN ?>/company/users/new" class="ul-btn-new" style="font-size:13px;padding:8px 18px">
          <i class="fa fa-plus"></i> Créer un utilisateur
        </a>
      </div>
    <?php endif; ?>

  </div><!-- /.ul-card -->

</div><!-- /.ul-container --> 