<?php
$host = 'localhost';
$db = 'ticket_concern';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];
$message = $_POST['message'];
$sender = $_POST['sender'];

if (!empty($email) && !empty($message) && in_array($sender, ['user', 'admin'])) {
  $stmt = $conn->prepare("INSERT INTO messages (email, message, sender) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $email, $message, $sender);
  $stmt->execute();
  $stmt->close();
}

$conn->close();
?>
