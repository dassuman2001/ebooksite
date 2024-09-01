<?php
session_start();
require 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$ebook_id = $_GET['ebook_id'];
$user_id = $_SESSION['user_id'];
$comment = $_POST['comment'];

$stmt = $conn->prepare("INSERT INTO comments (ebook_id, user_id, comment) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $ebook_id, $user_id, $comment);
$stmt->execute();
$stmt->close();

header("Location: view_book.php?id=$ebook_id");
?>
