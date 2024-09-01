<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require 'db.php';

// Ensure the user is logged in and is an author
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'author') {
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['book_id'])) {
    $book_id = intval($_GET['book_id']);
    
    // Fetch the book title
    $stmt = $conn->prepare("SELECT title FROM ebooks WHERE id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $book_result = $stmt->get_result();
    $book = $book_result->fetch_assoc();
    $book_title = htmlspecialchars($book['title']);
    $stmt->close();

    // Prepare and execute the query to fetch comments with user names
    $stmt = $conn->prepare("
        SELECT comments.id, comments.comment, comments.created_at, users.name AS user_name
        FROM comments
        JOIN users ON comments.user_id = users.id
        WHERE comments.ebook_id = ?
    ");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comments for Book</title>
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
        .comment {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 10px 0;
            padding: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .comment p {
            margin: 10px 0;
            color: #555;
        }
        .comment a {
            color: #dc3545;
            text-decoration: none;
        }
        .comment a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Comments for Book: <?php echo $book_title; ?></h1>
        <?php
        while ($row = $result->fetch_assoc()) {
            $comment_id = htmlspecialchars($row['id']);
            $user_name = htmlspecialchars($row['user_name']);
            $comment = htmlspecialchars($row['comment']);
            $created_at = htmlspecialchars($row['created_at']);
            ?>
            <div class="comment">
                <p><strong>User Name:</strong> <?php echo $user_name; ?></p>
                <p><strong>Comment:</strong> <?php echo $comment; ?></p>
                <p><strong>Created At:</strong> <?php echo $created_at; ?></p>
                <a href="delete_comment.php?comment_id=<?php echo $comment_id; ?>&book_id=<?php echo $book_id; ?>" onclick="return confirm('Are you sure you want to delete this comment?');">Delete Comment</a>
            </div>
            <?php
        }
        $stmt->close();
        ?>
    </div>
</body>
</html>

<?php
} else {
    echo "<p>Invalid request. Please ensure you provide the 'book_id' parameter in the URL.</p>";
}
?>
