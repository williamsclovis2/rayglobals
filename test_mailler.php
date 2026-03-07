<?php
require_once 'admin/core/init.php';

echo '<pre>';

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPDebug  = 2;
$mail->SMTPAuth   = true;
$mail->SMTPSecure = 'ssl';
$mail->Host       = 'premium301.web-hosting.com';
$mail->Port       = 465;
$mail->Username   = 'recrutement@rayglobals.org';
$mail->Password   = 'P@ssw2026!';
$mail->PluginDir  = 'admin/core/classes/';

$mail->CharSet = 'UTF-8';
$mail->SetFrom('recrutement@rayglobals.org', 'RayGlobals Recrutement');
$mail->AddAddress('clovismul@gmail.com', 'Clovis Test');  // ← your email here
$mail->Subject = 'Test email RayGlobals';
$mail->Body    = '<p>Bonjour, ceci est un email de test depuis RayGlobals.</p>';
$mail->AltBody = 'Bonjour, ceci est un email de test depuis RayGlobals.';
$mail->IsHTML(true);

if ($mail->Send()) {
    echo '✅ Email envoyé avec succès !';
} else {
    echo '❌ Échec : ' . $mail->ErrorInfo;
}

echo '</pre>';