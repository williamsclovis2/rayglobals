<?php
class JobApplicationController {

    // Relative path from project root — used to build the web URL
    const UPLOAD_REL = 'admin/media_data/stori/applications/';
    const UPLOAD_WEB = 'media_data/stori/applications/';

    // ── SMTP credentials (shared by all mailer calls) ──────────────
    const MAIL_HOST = 'premium301.web-hosting.com';
    const MAIL_PORT = 465;
    const MAIL_USER = 'recrutement@rayglobals.org';
    const MAIL_PASS = 'P@ssw2026!';
    const MAIL_FROM = 'recrutement@rayglobals.org';
    const MAIL_NAME = 'RayGlobals Recrutement';
    const MAIL_BCC  = 'jmulala@rayglobals.org';

    // ──────────────────────────────────────────────────────────────
    // SUBMIT
    // ──────────────────────────────────────────────────────────────
    public static function submit() {

        $diagnoArray = ['NO_ERRORS'];

        /* 1. Strip register- prefix */
        $_SUBMIT = [];
        foreach ($_POST as $k => $v) {
            $parts = explode('register-', $k);
            $_SUBMIT[end($parts)] = trim($v);
        }

        /* 2. Validation */
        $required = ['firstname','lastname','gender','email','phone','country','city','education','experience'];
        $errors   = [];
        foreach ($required as $f) {
            if (empty($_SUBMIT[$f])) $errors[] = "Le champ « {$f} » est obligatoire.";
        }
        if (!empty($_SUBMIT['email']) && !filter_var($_SUBMIT['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'adresse e-mail est invalide.";
        }
        if (empty($_FILES['file_cv']['name'])) {
            $errors[] = "Veuillez téléverser votre CV.";
        }
        if (!empty($errors)) {
            return (object)['ERRORS' => true, 'ERRORS_SCRIPT' => $errors];
        }

        /* 3. Build absolute upload directory */
        $docRoot    = rtrim(str_replace('\\','/', $_SERVER['DOCUMENT_ROOT']), '/');
        $scriptPath = str_replace('\\','/', $_SERVER['SCRIPT_FILENAME']);
        $projectDir = '';
        if (preg_match('#^'.preg_quote($docRoot,'#').'/([^/]+)/#', $scriptPath, $m)) {
            $projectDir = $m[1] . '/';
        }

        $job_id = (int)Input::get('job_id', 'post');
        $relPhys = self::UPLOAD_REL . $job_id . '/';
        $relDir  = self::UPLOAD_WEB . $job_id . '/';
        $absDir  = $docRoot . '/' . $projectDir . $relPhys;

        if (!is_dir($absDir)) {
            if (!mkdir($absDir, 0755, true)) {
                return (object)[
                    'ERRORS'        => true,
                    'ERRORS_SCRIPT' => ["Impossible de créer le dossier: {$absDir} — vérifiez les permissions."]
                ];
            }
        }

        /* 4. Slug for filenames */
        $slug = self::_slug($_SUBMIT['firstname'], $_SUBMIT['lastname']);

        /* 5. Upload CV (required) */
        $cvResult = self::_uploadDoc($_FILES['file_cv'], $absDir, $relDir, $slug . '_cv');
        if ($cvResult['error']) {
            return (object)['ERRORS' => true, 'ERRORS_SCRIPT' => [$cvResult['message']]];
        }
        $path_cv = $cvResult['rel_path'];

        /* 6. Cover letter (optional) */
        $path_cover = '';
        if (!empty($_FILES['file_cover']['name']) && $_FILES['file_cover']['error'] === UPLOAD_ERR_OK) {
            $r = self::_uploadDoc($_FILES['file_cover'], $absDir, $relDir, $slug . '_lettre');
            if (!$r['error']) $path_cover = $r['rel_path'];
        }

        /* 7. Diplomas (optional, multiple) */
        $diplomaPaths = [];
        if (!empty($_FILES['file_diplomas']) && is_array($_FILES['file_diplomas']['name'])) {
            $count = count($_FILES['file_diplomas']['name']);
            for ($i = 0; $i < $count; $i++) {
                if ($_FILES['file_diplomas']['error'][$i] !== UPLOAD_ERR_OK) continue;
                if (empty($_FILES['file_diplomas']['name'][$i]))              continue;
                if ($_FILES['file_diplomas']['size'][$i] === 0)               continue;

                $single = [
                    'name'     => $_FILES['file_diplomas']['name'][$i],
                    'type'     => $_FILES['file_diplomas']['type'][$i],
                    'tmp_name' => $_FILES['file_diplomas']['tmp_name'][$i],
                    'error'    => $_FILES['file_diplomas']['error'][$i],
                    'size'     => $_FILES['file_diplomas']['size'][$i],
                ];
                $r = self::_uploadDoc($single, $absDir, $relDir, $slug . '_diplome' . ($i + 1));
                if (!$r['error']) $diplomaPaths[] = $r['rel_path'];
            }
        }

        /* 8. Insert */
        try {
            $appTable = new JobApplication();
            $newId    = $appTable->insert([
                'job_id'           => $job_id,
                'job_title'        => Input::get('job_title',    'post'),
                'company_name'     => Input::get('company_name', 'post'),
                'job_type'         => Input::get('job_type',     'post'),
                'job_location'     => Input::get('job_location', 'post'),
                'job_category'     => Input::get('job_category', 'post'),

                'firstname'        => $_SUBMIT['firstname'],
                'lastname'         => $_SUBMIT['lastname'],
                'gender'           => $_SUBMIT['gender'],
                'dob'              => !empty($_SUBMIT['dob']) ? $_SUBMIT['dob'] : null,
                'email'            => $_SUBMIT['email'],
                'phone'            => $_SUBMIT['phone'],
                'country'          => $_SUBMIT['country'],
                'city'             => $_SUBMIT['city'],
                'education'        => $_SUBMIT['education'],
                'experience'       => $_SUBMIT['experience'],
                'current_position' => $_SUBMIT['current_position'] ?? '',
                'linkedin'         => $_SUBMIT['linkedin']         ?? '',

                'file_cv'          => $path_cv,
                'file_cover'       => $path_cover,
                'file_diplomas'    => !empty($diplomaPaths)
                                        ? json_encode($diplomaPaths, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                                        : null,

                'message'          => $_SUBMIT['message'] ?? '',
                'status'           => 'pending',
                'ip_address'       => $_SERVER['REMOTE_ADDR'] ?? '',
            ]);

            // ── Send confirmation email to applicant ──────────────
            self::_sendEmail_Confirmation(
                $_SUBMIT['email'],
                $_SUBMIT['firstname'],
                $_SUBMIT['lastname'],
                Input::get('job_title',    'post'),
                Input::get('company_name', 'post')
            );

        } catch (Throwable $e) {
            $diagnoArray[0] = 'ERRORS_FOUND';
            $diagnoArray[]  = $e->getMessage();
        }

        if ($diagnoArray[0] === 'ERRORS_FOUND') {
            return (object)['ERRORS' => true, 'ERRORS_SCRIPT' => $diagnoArray];
        }
        return (object)['ERRORS' => false, 'SUCCESS' => true, 'APP_ID' => $newId ?? 0];
    }

    // ──────────────────────────────────────────────────────────────
    // CHANGE STATUS
    // ──────────────────────────────────────────────────────────────
    public static function changeStatus($status, $app_ID) {
        $allowed = ['pending','accepted','rejected','reviewing'];
        if (!in_array($status, $allowed)) {
            return (object)['ERRORS' => true, 'ERRORS_SCRIPT' => ['Statut invalide.']];
        }

        try {
            // Load application data BEFORE updating so we have the email/name
            $appTable = new JobApplication();
            if (!$appTable->find((int)$app_ID)) {
                return (object)['ERRORS' => true, 'ERRORS_SCRIPT' => ['Candidature introuvable.']];
            }
            $appData = $appTable->first();

            // Update status in DB
            (new JobApplication())->update(['status' => $status], (int)$app_ID);

            // Send status email to applicant
            if ($status === 'accepted') {
                self::_sendEmail_Accepted(
                    $appData->email,
                    $appData->firstname,
                    $appData->lastname,
                    $appData->job_title,
                    $appData->company_name
                );
            } elseif ($status === 'rejected') {
                self::_sendEmail_Rejected(
                    $appData->email,
                    $appData->firstname,
                    $appData->lastname,
                    $appData->job_title,
                    $appData->company_name
                );
            }

        } catch (Throwable $e) {
            return (object)['ERRORS' => true, 'ERRORS_SCRIPT' => [$e->getMessage()]];
        }

        return (object)['ERRORS' => false, 'SUCCESS' => true];
    }

    // ──────────────────────────────────────────────────────────────
    // GET ALL
    // ──────────────────────────────────────────────────────────────
    public static function getAll($filters = [], $sort = '`ID` DESC', $offset = 0, $limit = 20) {
        $sql    = "SELECT * FROM `job_applications` WHERE 1";
        $params = [];

        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $sql .= " AND `status` = ?"; $params[] = $filters['status'];
        }
        if (!empty($filters['job_id'])) {
            $sql .= " AND `job_id` = ?"; $params[] = (int)$filters['job_id'];
        }
        if (!empty($filters['keyword'])) {
            $kw   = '%' . $filters['keyword'] . '%';
            $sql .= " AND (`firstname` LIKE ? OR `lastname` LIKE ? OR `email` LIKE ?
                           OR `job_title` LIKE ? OR `company_name` LIKE ?)";
            array_push($params, $kw, $kw, $kw, $kw, $kw);
        }

        $cols = ['`ID`','`status`','`created_date`','`job_title`','`lastname`'];
        $dir  = strpos($sort, 'ASC') !== false ? 'ASC' : 'DESC';
        $col  = '`ID`';
        foreach ($cols as $c) { if (strpos($sort, $c) !== false) { $col = $c; break; } }
        $sql .= " ORDER BY {$col} {$dir} LIMIT ".(int)$limit." OFFSET ".(int)$offset;

        $t = new JobApplication();
        $t->selectQuery($sql, $params);
        return $t;
    }

    // ──────────────────────────────────────────────────────────────
    // COUNT ALL
    // ──────────────────────────────────────────────────────────────
    public static function countAll($filters = []) {
        $sql = "SELECT COUNT(*) as cnt FROM `job_applications` WHERE 1";
        $params = [];
        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $sql .= " AND `status` = ?"; $params[] = $filters['status'];
        }
        if (!empty($filters['keyword'])) {
            $kw   = '%' . $filters['keyword'] . '%';
            $sql .= " AND (`firstname` LIKE ? OR `lastname` LIKE ? OR `email` LIKE ?
                           OR `job_title` LIKE ? OR `company_name` LIKE ?)";
            array_push($params, $kw, $kw, $kw, $kw, $kw);
        }
        $t = new JobApplication();
        $t->selectQuery($sql, $params);
        return $t->count() ? (int)($t->first()->cnt ?? 0) : 0;
    }

    // ──────────────────────────────────────────────────────────────
    // STATS
    // ──────────────────────────────────────────────────────────────
    public static function getStats() {
        $t = new JobApplication();
        $t->selectQuery("SELECT `status`, COUNT(*) as cnt FROM `job_applications` GROUP BY `status`");
        $s = ['pending'=>0,'reviewing'=>0,'accepted'=>0,'rejected'=>0,'total'=>0];
        if ($t->count()) {
            foreach ($t->data() as $row) {
                if (isset($s[$row->status])) $s[$row->status] = (int)$row->cnt;
            }
        }
        $s['total'] = array_sum(array_diff_key($s, ['total'=>0]));
        return (object)$s;
    }

    // ──────────────────────────────────────────────────────────────
    // DELETE
    // ──────────────────────────────────────────────────────────────
    public static function delete($app_ID) {
        $app_ID = (int)$app_ID;
        $t      = new JobApplication();
        if (!$t->find($app_ID)) {
            return (object)['ERRORS' => true, 'ERRORS_SCRIPT' => ['Candidature introuvable.']];
        }
        $data    = $t->first();
        $docRoot = rtrim(str_replace('\\','/',$_SERVER['DOCUMENT_ROOT']),'/');
        $scriptPath = str_replace('\\','/',$_SERVER['SCRIPT_FILENAME']);
        $projectDir = '';
        if (preg_match('#^'.preg_quote($docRoot,'#').'/([^/]+)/#', $scriptPath, $m)) {
            $projectDir = $m[1] . '/';
        }
        $base    = $docRoot . '/' . $projectDir;
        $baseAdm = $base . 'admin/';

        self::_deleteDoc($baseAdm . ltrim($data->file_cv    ?? '', '/'));
        self::_deleteDoc($baseAdm . ltrim($data->file_cover ?? '', '/'));
        if (!empty($data->file_diplomas)) {
            $dips = json_decode($data->file_diplomas, true);
            if (is_array($dips)) foreach ($dips as $dp) self::_deleteDoc($baseAdm . ltrim($dp, '/'));
        }
        try {
            DB::getInstance()->delete('job_applications', ['ID','=',$app_ID]);
        } catch (Throwable $e) {
            return (object)['ERRORS' => true, 'ERRORS_SCRIPT' => [$e->getMessage()]];
        }
        return (object)['ERRORS' => false, 'SUCCESS' => true];
    }

    // ══════════════════════════════════════════════════════════════
    // PRIVATE HELPERS — FILE HANDLING
    // ══════════════════════════════════════════════════════════════

    private static function _uploadDoc(array $file, string $absDir, string $relDir, string $name): array {
        $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['pdf','doc','docx','jpg','jpeg','png'];
        if (!in_array($ext, $allowed)) {
            return ['error'=>true,'rel_path'=>'','message'=>"Format non autorisé: {$ext} pour {$name}."];
        }
        if ($file['size'] > 5 * 1024 * 1024) {
            return ['error'=>true,'rel_path'=>'','message'=>"Fichier trop volumineux (max 5 Mo): {$name}."];
        }

        $filename = $name . '_' . substr(md5(uniqid()), 0, 4) . '.' . $ext;
        $absPath  = $absDir . $filename;
        $relPath  = $relDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $absPath)) {
            return [
                'error'    => true,
                'rel_path' => '',
                'message'  => "Échec move_uploaded_file vers: {$absPath}"
            ];
        }
        return ['error'=>false, 'rel_path'=>$relPath, 'message'=>''];
    }

    private static function _slug(string $a, string $b): string {
        $s = strtolower(trim($a).'_'.trim($b));
        $s = strtr($s, ['à'=>'a','â'=>'a','é'=>'e','è'=>'e','ê'=>'e','ë'=>'e','î'=>'i','ï'=>'i','ô'=>'o','ù'=>'u','û'=>'u','ü'=>'u','ç'=>'c','ñ'=>'n']);
        $s = preg_replace('/[^a-z0-9_]/','_',$s);
        $s = preg_replace('/_+/','_',$s);
        return trim($s,'_') ?: 'applicant';
    }

    private static function _deleteDoc(string $path): void {
        $path = str_replace('\\','/',$path);
        if (!empty($path) && file_exists($path)) @unlink($path);
    }

    // ══════════════════════════════════════════════════════════════
    // PRIVATE HELPERS — EMAIL ENGINE
    // ══════════════════════════════════════════════════════════════

    private static function _sendMail(string $to, string $toName, string $subject, string $htmlBody, string $altBody): void {
        try {
            $mail = new PHPMailer();           // PHPMailer v5.2 — no (true) needed
            $mail->IsSMTP();
            $mail->SMTPDebug  = 0;             // Set to 1 temporarily to debug
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = 'ssl';
            $mail->Host       = self::MAIL_HOST;
            $mail->Port       = self::MAIL_PORT;
            $mail->Username   = self::MAIL_USER;
            $mail->Password   = self::MAIL_PASS;

            // Tell PHPMailer where class.smtp.php lives
            $mail->PluginDir  = dirname(__FILE__) . '/';

            $mail->CharSet    = 'UTF-8';
            $mail->SetFrom(self::MAIL_FROM, self::MAIL_NAME);
            $mail->AddAddress($to, $toName);
            $mail->AddBCC(self::MAIL_BCC, self::MAIL_NAME);   // always copy admin
            $mail->AddReplyTo(self::MAIL_FROM, self::MAIL_NAME);

            $mail->Subject = $subject;
            $mail->Body    = $htmlBody;
            $mail->AltBody = $altBody;
            $mail->IsHTML(true);

            $mail->Send();

        } catch (phpmailerException $e) {
            @error_log('[JobApplication] PHPMailer error → ' . $to . ': ' . $e->getMessage());
        } catch (Exception $e) {
            @error_log('[JobApplication] Mail error → ' . $to . ': ' . $e->getMessage());
        }
    }

  
    private static function _buildEmailHtml(
        string $accentColor,
        string $iconEmoji,
        string $iconBg,
        string $titleText,
        string $subtitleText,
        string $bodyHtml,
        string $job,
        string $co
    ): string {
        return '<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#f4f6f9;font-family:Arial,Helvetica,sans-serif">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f9;padding:40px 0">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0"
           style="background:#fff;border-radius:12px;overflow:hidden;
                  box-shadow:0 4px 20px rgba(0,0,0,.08);max-width:600px;width:100%">

      <!-- Header bar -->
      <tr>
        <td style="background:' . $accentColor . ';padding:32px 40px;text-align:center">
          <h1 style="margin:0;color:#fff;font-size:21px;font-weight:700;letter-spacing:.4px">RayGlobals</h1>
          <p style="margin:5px 0 0;color:rgba(255,255,255,.78);font-size:13px">Équipe de Recrutement</p>
        </td>
      </tr>

      <!-- Icon + title -->
      <tr>
        <td align="center" style="padding:32px 40px 0">
          <div style="width:64px;height:64px;border-radius:50%;background:' . $iconBg . ';
                      display:inline-block;line-height:64px;font-size:30px;
                      text-align:center;margin-bottom:14px">' . $iconEmoji . '</div>
          <h2 style="margin:0 0 6px;color:#0f172a;font-size:19px;font-weight:700">' . $titleText . '</h2>
          <p style="margin:0;color:#64748b;font-size:13px">' . $subtitleText . '</p>
        </td>
      </tr>

      <!-- Body -->
      <tr>
        <td style="padding:24px 40px 36px">
          ' . $bodyHtml . '

          <!-- Job info box -->
          <table width="100%" cellpadding="0" cellspacing="0"
                 style="background:#f0f7ff;border-left:4px solid ' . $accentColor . ';
                        border-radius:0 8px 8px 0;margin:22px 0">
            <tr>
              <td style="padding:14px 18px">
                <p style="margin:0 0 5px;color:#1e40af;font-size:12px;font-weight:700;
                          text-transform:uppercase;letter-spacing:.5px">📋 Votre candidature</p>
                <p style="margin:0;color:#1e293b;font-size:14px;line-height:1.6">
                  <strong>Poste :</strong> ' . htmlspecialchars($job) . '<br>
                  <strong>Entreprise :</strong> ' . htmlspecialchars($co) . '
                </p>
              </td>
            </tr>
          </table>

          <p style="margin:16px 0 0;color:#334155;font-size:14px;line-height:1.7">
            Cordialement,<br>
            <strong style="color:#0f172a">L\'équipe de recrutement</strong><br>
            <span style="color:#64748b;font-size:12px">RayGlobals</span>
          </p>
        </td>
      </tr>

      <!-- Footer -->
      <tr>
        <td style="background:#f8fafc;border-top:1px solid #e2e8f0;padding:18px 40px;text-align:center">
          <p style="margin:0;color:#94a3b8;font-size:11px;line-height:1.6">
            Cet e-mail est automatique — merci de ne pas y répondre.<br>
            &copy; ' . date('Y') . ' RayGlobals — Tous droits réservés
          </p>
        </td>
      </tr>

    </table>
  </td></tr>
</table>
</body></html>';
    }

    // ──────────────────────────────────────────────────────────────
    // EMAIL 1 — Confirmation de réception (on submit)
    // ──────────────────────────────────────────────────────────────
    private static function _sendEmail_Confirmation(
        string $to, string $first, string $last, string $job, string $co
    ): void {
        $name    = trim("$first $last");
        $subject = "Confirmation de réception de votre candidature";

        $bodyHtml = '
          <p style="margin:0 0 14px;color:#1e293b;font-size:15px;line-height:1.7">
            Bonjour <strong>' . htmlspecialchars($first) . '</strong>,
          </p>
          <p style="margin:0 0 14px;color:#334155;font-size:15px;line-height:1.7">
            Nous vous remercions d\'avoir soumis votre candidature.
          </p>
          <p style="margin:0 0 14px;color:#334155;font-size:15px;line-height:1.7">
            Nous vous confirmons que votre dossier de candidature a bien été reçu.
            Notre équipe procédera à l\'examen attentif de votre CV ainsi que des documents soumis.
          </p>
          <p style="margin:0 0 14px;color:#334155;font-size:15px;line-height:1.7">
            Seuls les candidats présélectionnés seront contactés pour la suite du processus,
            notamment pour une invitation à un entretien.
          </p>
          <p style="margin:0 0 14px;color:#334155;font-size:15px;line-height:1.7">
            Veuillez noter qu\'il n\'est pas nécessaire de répondre à cet e-mail.
          </p>
          <p style="margin:0;color:#334155;font-size:15px;line-height:1.7">
            Nous vous remercions pour l\'intérêt que vous portez à notre organisation
            et vous souhaitons bonne chance pour la suite.
          </p>';

        $altBody = "Bonjour {$first},\n\n"
                 . "Nous vous remercions d'avoir soumis votre candidature.\n\n"
                 . "Nous vous confirmons que votre dossier pour le poste de \"{$job}\" chez {$co} a bien été reçu.\n"
                 . "Notre équipe procédera à l'examen attentif de votre CV ainsi que des documents soumis.\n\n"
                 . "Seuls les candidats présélectionnés seront contactés pour la suite du processus.\n\n"
                 . "Veuillez noter qu'il n'est pas nécessaire de répondre à cet e-mail.\n\n"
                 . "Nous vous remercions pour l'intérêt que vous portez à notre organisation "
                 . "et vous souhaitons bonne chance pour la suite.\n\n"
                 . "Cordialement,\nL'équipe de recrutement\nRayGlobals";

        $html = self::_buildEmailHtml(
            'linear-gradient(135deg,#1a3a6b,#2563eb)',
            '✅', '#dcfce7',
            'Candidature reçue !',
            'Référence : ' . htmlspecialchars($job),
            $bodyHtml, $job, $co
        );

        self::_sendMail($to, $name, $subject, $html, $altBody);
    }

    // ──────────────────────────────────────────────────────────────
    // EMAIL 2 — Invitation à un entretien (status = accepted)
    // ──────────────────────────────────────────────────────────────
    private static function _sendEmail_Accepted(
        string $to, string $first, string $last, string $job, string $co
    ): void {
        $name    = trim("$first $last");
        $subject = "Suite à votre candidature – Invitation à un entretien";

        $bodyHtml = '
          <p style="margin:0 0 14px;color:#1e293b;font-size:15px;line-height:1.7">
            Bonjour <strong>' . htmlspecialchars($first) . '</strong>,
          </p>
          <p style="margin:0 0 14px;color:#334155;font-size:15px;line-height:1.7">
            Nous vous remercions pour l\'intérêt que vous avez porté à notre organisation
            en soumettant votre candidature.
          </p>
          <p style="margin:0 0 14px;color:#334155;font-size:15px;line-height:1.7">
            Après examen de votre CV et des documents fournis, nous avons le plaisir de vous informer
            que votre candidature a été <strong style="color:#16a34a">présélectionnée</strong>
            pour la prochaine étape du processus de recrutement.
          </p>
          <p style="margin:0 0 14px;color:#334155;font-size:15px;line-height:1.7">
            Nous vous contacterons prochainement afin de vous communiquer les détails
            concernant l\'entretien.
          </p>
          <p style="margin:0;color:#334155;font-size:15px;line-height:1.7">
            Nous vous remercions encore pour votre intérêt et nous réjouissons
            de pouvoir échanger avec vous.
          </p>';

        $altBody = "Bonjour {$first},\n\n"
                 . "Nous vous remercions pour l'intérêt que vous avez porté à notre organisation.\n\n"
                 . "Après examen de votre CV et des documents fournis, nous avons le plaisir de vous informer "
                 . "que votre candidature pour le poste de \"{$job}\" chez {$co} a été présélectionnée "
                 . "pour la prochaine étape du processus de recrutement.\n\n"
                 . "Nous vous contacterons prochainement afin de vous communiquer les détails concernant l'entretien.\n\n"
                 . "Nous vous remercions encore pour votre intérêt et nous réjouissons de pouvoir échanger avec vous.\n\n"
                 . "Cordialement,\nL'équipe de recrutement\nRayGlobals";

        $html = self::_buildEmailHtml(
            'linear-gradient(135deg,#14532d,#16a34a)',
            '🎉', '#dcfce7',
            'Félicitations — Candidature présélectionnée !',
            'Prochaine étape : invitation à un entretien',
            $bodyHtml, $job, $co
        );

        self::_sendMail($to, $name, $subject, $html, $altBody);
    }

    // ──────────────────────────────────────────────────────────────
    // EMAIL 3 — Candidature non retenue (status = rejected)
    // ──────────────────────────────────────────────────────────────
    private static function _sendEmail_Rejected(
        string $to, string $first, string $last, string $job, string $co
    ): void {
        $name    = trim("$first $last");
        $subject = "Suite à votre candidature";

        $bodyHtml = '
          <p style="margin:0 0 14px;color:#1e293b;font-size:15px;line-height:1.7">
            Bonjour <strong>' . htmlspecialchars($first) . '</strong>,
          </p>
          <p style="margin:0 0 14px;color:#334155;font-size:15px;line-height:1.7">
            Nous vous remercions sincèrement pour l\'intérêt que vous avez porté à notre organisation
            ainsi que pour le temps consacré à la soumission de votre candidature.
          </p>
          <p style="margin:0 0 14px;color:#334155;font-size:15px;line-height:1.7">
            Après un examen attentif de votre dossier, nous regrettons de vous informer que votre
            candidature n\'a pas été retenue pour la suite du processus de recrutement.
          </p>
          <p style="margin:0 0 14px;color:#334155;font-size:15px;line-height:1.7">
            Nous vous remercions encore pour votre intérêt et vous souhaitons plein succès
            dans vos projets professionnels futurs.
          </p>
          <p style="margin:0;color:#334155;font-size:15px;line-height:1.7">
            Veuillez noter qu\'il n\'est pas nécessaire de répondre à cet e-mail.
          </p>';

        $altBody = "Bonjour {$first},\n\n"
                 . "Nous vous remercions sincèrement pour l'intérêt que vous avez porté à notre organisation "
                 . "ainsi que pour le temps consacré à la soumission de votre candidature.\n\n"
                 . "Après un examen attentif de votre dossier pour le poste de \"{$job}\" chez {$co}, "
                 . "nous regrettons de vous informer que votre candidature n'a pas été retenue "
                 . "pour la suite du processus de recrutement.\n\n"
                 . "Nous vous remercions encore pour votre intérêt et vous souhaitons plein succès "
                 . "dans vos projets professionnels futurs.\n\n"
                 . "Veuillez noter qu'il n'est pas nécessaire de répondre à cet e-mail.\n\n"
                 . "Cordialement,\nL'équipe de recrutement\nRayGlobals";

        $html = self::_buildEmailHtml(
            'linear-gradient(135deg,#7f1d1d,#dc2626)',
            '📋', '#fee2e2',
            'Suite à votre candidature',
            'Merci pour votre intérêt',
            $bodyHtml, $job, $co
        );

        self::_sendMail($to, $name, $subject, $html, $altBody);
    }
}