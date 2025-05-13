<?php

require_once('../../config/conn.php');

if (isset($_POST['submit'])) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $event_name = $_POST['event_name'] ?? '';
    $ticket_id = $_POST['ticket_id'] ?? '';
    $current_seat = $_POST['current_seat'] ?? '';
    $description = $_POST['description'] ?? '';

    if (empty($name) || empty($email) || empty($description) || empty($ticket_id) || empty($event_name)) {
        echo "<script>
                alert('Please fill in all required fields.');
                window.location.href = '../submit_seat_plan_concern.php';
              </script>";
        exit();
    }

    $stmt = $pdo->prepare("INSERT INTO seat_concern (full_name, email_address, event_name, ticket_id, seat_number, description, status) 
                           VALUES (?, ?, ?, ?, ?, ?, ?)");

    if ($stmt->execute([$name, $email, $event_name, $ticket_id, $current_seat, $description, 'Pending'])) {
        echo "<script>
                alert('Your concern has been submitted successfully!');
                window.history.back();
              </script>";
    } else {
        echo "<script>
                alert('There was an error submitting your concern. Please try again later.');
                window.history.back();
              </script>";
    }

    $checkStmt = $pdo->prepare("SELECT 1 FROM client_emails WHERE email_address = ?");
    $checkStmt->execute([$email]);
    if (!$checkStmt->fetch()) {
        $stmt1 = $pdo->prepare("INSERT INTO client_emails (email_address) VALUES (?)");
        $stmt1->execute([$email]);
    }
}

$pdo = null;

?>
