<?php
session_start();
require 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Welcome to Your Dashboard</h1>
        
        <div class="list-group">
            <?php if ($role == 'author'): ?>
                <a href="submit_work.php" class="list-group-item list-group-item-action">Start New Work</a>
                <a href="resume_work.php" class="list-group-item list-group-item-action">Resume Old Work</a>
                <a href="edit_profile.php" class="list-group-item list-group-item-action">Edit Profile</a>
                <a href="view_works.php" class="list-group-item list-group-item-action">Manage Works</a>

            <?php elseif ($role == 'user'): ?>
                <a href="view_books.php" class="list-group-item list-group-item-action">View Books</a>
            <?php endif; ?>
        </div>
        
        <a href="logout.php" class="btn btn-danger mt-4">Logout</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
