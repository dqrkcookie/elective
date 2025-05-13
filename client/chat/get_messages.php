<?php
$host = 'localhost';
$db = 'ticket_concern';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$email = $_GET['email'] ?? '';

$messages = [];

if (!empty($email)) {
  $stmt = $conn->prepare("SELECT * FROM messages WHERE email = ? ORDER BY timestamp ASC");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
  }
  $stmt->close();
}

echo json_encode($messages);
$conn->close();
?>
