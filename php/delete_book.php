<?php
session_start();
require 'db.php';

$book_id = intval($_GET['book_id']);
$stmt = $conn->prepare("DELETE FROM ebooks WHERE id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$stmt->close();

header("Location: view_works.php");
?>
