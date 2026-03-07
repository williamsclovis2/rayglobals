<?php
/**
 * apply-handler.php
 * Receives the multipart POST from apply.php, processes the application,
 * then returns a JSON response consumed by the JS success popup.
 */

/*
 * CRITICAL: capture ?job_enc_id= before init.php re-parses $_GET.
 */
$_RAW_QUERY = $_SERVER['QUERY_STRING'] ?? '';

require_once "admin/core/init.php";

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . DN . '/vacancies.php');
    exit;
}

// Basic token / spam check
$webToken = Input::get('webToken', 'post');
if (empty($webToken)) {
    self::_jsonResponse(false, 'Requête invalide.');
}

// Process the application
$form = JobApplicationController::submit();

header('Content-Type: application/json; charset=utf-8');

if ($form->ERRORS) {
    $msg = is_array($form->ERRORS_SCRIPT)
        ? implode(' ', $form->ERRORS_SCRIPT)
        : 'Une erreur est survenue. Veuillez vérifier vos informations.';

    echo json_encode([
        'success' => false,
        'message' => $msg,
    ]);
    exit;
}

echo json_encode([
    'success' => true,
    'message' => 'Votre candidature a bien été envoyée ! Vérifiez votre boîte e-mail pour plus d\'informations.',
]);
exit;