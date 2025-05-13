<?php
$host = 'localhost';
$db = 'ticket_concern';
$user = 'root';
$pass = '';

$email = isset($_POST['email']) ? $_POST['email'] : '';
$message = isset($_POST['message']) ? $_POST['message'] : '';
$sender = isset($_POST['sender']) ? $_POST['sender'] : 'user';

if (empty($email) || empty($message)) {
  http_response_code(400);
  echo json_encode(['error' => 'Email and message are required']);
  exit;
}

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode(['error' => 'Database connection failed']);
  exit;
}

$stmt = $conn->prepare("INSERT INTO messages (email, message, sender, timestamp) VALUES (?, ?, ?, NOW())");
$stmt->bind_param('sss', $email, $message, $sender);

if ($stmt->execute()) {
  echo json_encode(['success' => true, 'message_id' => $conn->insert_id]);
} else {
  http_response_code(500);
  echo json_encode(['error' => 'Failed to save message']);
}

$stmt->close();
$conn->close();
?>