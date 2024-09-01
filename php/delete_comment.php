<?php

session_start();
require 'db.php';

// Ensure the user is logged in and is an author
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'author') {
    header("Location: ../index.php");
    exit;
}

// Check if comment_id and book_id are set
if (isset($_GET['comment_id']) && isset($_GET['book_id'])) {
    $comment_id = intval($_GET['comment_id']);
    $book_id = intval($_GET['book_id']);

    // Prepare and execute the query to delete the comment
    $stmt = $conn->prepare("DELETE FROM comments WHERE id = ?");
    $stmt->bind_param("i", $comment_id);

    if ($stmt->execute()) {
        // Successful deletion, redirect back to view comments page
        header("Location: view_comments.php?book_id=" . $book_id);
    } else {
        // Error occurred, show a message
        echo "Error deleting comment.";
    }

    $stmt->close();
} else {
    echo "Invalid request. Please ensure you provide the 'comment_id' and 'book_id' parameters in the URL.";
}
?>
