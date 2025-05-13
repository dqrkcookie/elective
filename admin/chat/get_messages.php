<?php
$host = 'localhost';
$db = 'ticket_concern';
$user = 'root';
$pass = '';

$email = isset($_GET['email']) ? $_GET['email'] : '';

if (empty($email)) {
  http_response_code(400);
  echo json_encode(['error' => 'Email parameter is required']);
  exit;
}

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT * FROM messages WHERE email = ? ORDER BY timestamp ASC");
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];

while ($row = $result->fetch_assoc()) {
  $messages[] = $row;
}

echo json_encode($messages);

$stmt->close();
$conn->close();
?>