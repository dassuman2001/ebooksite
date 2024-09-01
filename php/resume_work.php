<?php
session_start();
require 'db.php';

// Redirect if not author
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'author') {
    header("Location: ../index.php");
    exit;
}

$author_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM ebooks WHERE author_id = $author_id AND status = 'pending'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume Work</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Resume Your Work</h1>
        <ul class="list-group">
            <?php while ($row = $result->fetch_assoc()): ?>
                <li class="list-group-item">
                    <?php echo htmlspecialchars($row['title']); ?>
                    <a href='submit_work.php?id=<?php echo $row['id']; ?>' class="btn btn-sm btn-outline-secondary float-end">Edit</a>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
