<?php

require_once ('../../config/conn.php');

if(isset($_GET['assist'])){
    header("Location: ../chat/chat.php?email=".$_GET['email']);
    exit();
}

if(isset($_GET['resolve'])){
    $id = $_GET['id'] ?? '';
    $table_name = $_GET['table_name'] ?? '';
    $file_name = $_GET['file_name'] ?? '';

    if(empty($id)){
        echo "<script>alert('Invalid request.');</script>";
        header("Location: ../index.php");
        exit();
    }

    $stmt = $pdo->prepare("UPDATE $table_name SET status = 'Closed' WHERE id = ?");
    if($stmt->execute([$id])){
        echo "<script>alert('Ticket Closed.'); window.location.replace('../$file_name');</script>";
    }else{
        echo "<script>alert('There was an error deleting the concern. Please try again later.'); window.history.back();</script>";
    }
}

$pdo = null;

?>