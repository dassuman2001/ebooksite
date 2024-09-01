<?php
session_start();
require 'db.php';

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Approve or Reject Book
if (isset($_GET['approve']) || isset($_GET['reject'])) {
    $book_id = $_GET['approve'] ?? $_GET['reject'];
    $approved = isset($_GET['approve']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE ebooks SET approved = ? WHERE id = ?");
    $stmt->bind_param("ii", $approved, $book_id);
    $stmt->execute();
    $stmt->close();
    
    header("Location: admin.php");
    exit();
}
?>
