<?php
session_start();
require 'db.php';

// Redirect if not author
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'author') {
    header("Location: ../index.php");
    exit;
}

$author_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM ebooks WHERE author_id = $author_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Works</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .book {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 10px 0;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .book h3 {
            margin-top: 0;
        }
        .book p {
            margin: 10px 0;
            color: #555;
        }
        .book a {
            color: #007bff;
            text-decoration: none;
            margin-right: 10px;
        }
        .book a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Works</h1>
        <?php
        while ($row = $result->fetch_assoc()) {
            $book_id = htmlspecialchars($row['id']);
            $title = htmlspecialchars($row['title']);
            $status = htmlspecialchars($row['status']);
            $format = htmlspecialchars($row['format']);
            ?>
            <div class="book">
                <h3><?php echo $title; ?></h3>
                <p>Status: <?php echo $status; ?></p>
                <a href="view_comments.php?book_id=<?php echo $book_id; ?>">Manage Comments</a> |
                <a href="delete_book.php?book_id=<?php echo $book_id; ?>" onclick="return confirm('Are you sure you want to delete this book?');">Delete Book</a> |
                <a href="export_book.php?book_id=<?php echo $book_id; ?>&format=<?php echo $format; ?>">Export as <?php echo $format; ?></a>
            </div>
            <?php
        }
        ?>
    </div>
</body>
</html>
