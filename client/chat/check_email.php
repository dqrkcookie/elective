<?php
require_once('../../config/conn.php');

$email = $_GET['email'] ?? '';

$response = ['exists' => false];

if (!empty($email)) {
    $stmt = $pdo->prepare("SELECT 1 FROM client_emails WHERE email_address = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $response['exists'] = true;
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>