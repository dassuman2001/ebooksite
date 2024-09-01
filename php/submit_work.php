<?php
session_start();
require 'db.php';

// Redirect if not author
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'author') {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $format = $_POST['format'];
    $author_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO ebooks (author_id, title, content, format) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $author_id, $title, $content, $format);
    $stmt->execute();
    $stmt->close();

    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Work</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Submit Your Work</h1>
        <form action="submit_work.php" method="POST">
            <div class="mb-3">
                <input type="text" class="form-control" name="title" placeholder="Title" required>
            </div>
            <div class="mb-3">
                <textarea class="form-control" name="content" rows="5" placeholder="Write your work here..." required></textarea>
            </div>
            <div class="mb-3">
                <select class="form-select" name="format" required>
                    <option value="word">Word</option>
                    <option value="pdf">PDF</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
