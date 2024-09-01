<?php
session_start();
require 'db.php';

if (!isset($_GET['id'])) {
    header("Location: view_books.php");
    exit();
}

$book_id = $_GET['id'];

// Get book details
$stmt = $conn->prepare("SELECT * FROM ebooks WHERE id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$book = $stmt->get_result()->fetch_assoc();

// Get comments for this book along with the user's name
$comments_stmt = $conn->prepare("
    SELECT comments.comment, users.name 
    FROM comments 
    JOIN users ON comments.user_id = users.id 
    WHERE comments.ebook_id = ?");
$comments_stmt->bind_param("i", $book_id);
$comments_stmt->execute();
$comments = $comments_stmt->get_result();

// Handle new comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $user_id = $_SESSION['user_id']; // Assuming user is logged in
    $comment_text = $_POST['comment'];

    $comment_stmt = $conn->prepare("INSERT INTO comments (ebook_id, user_id, comment) VALUES (?, ?, ?)");
    $comment_stmt->bind_param("iis", $book_id, $user_id, $comment_text);
    $comment_stmt->execute();

    header("Location: view_book.php?id=" . $book_id); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($book['title']); ?></title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Add Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1><?php echo htmlspecialchars($book['title']); ?></h1>
        <!-- <p><strong>Format:</strong> <?php echo htmlspecialchars($book['format']); ?></p> -->
        <p>Description: <?php echo nl2br(htmlspecialchars($book['content'])); ?></p>

        <!-- Comment Section -->
        <h2>Comments</h2>
        <div class="list-group mb-3">
            <?php while ($comment = $comments->fetch_assoc()) : ?>
                <div class="list-group-item">
                    <p><?php echo htmlspecialchars($comment['comment']); ?></p>
                    <small>Commented by: <?php echo htmlspecialchars($comment['name']); ?></small>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Add a new comment form -->
        <h3>Add a Comment</h3>
        <form action="view_book.php?id=<?php echo $book_id; ?>" method="POST">
            <div class="mb-3">
                <textarea name="comment" class="form-control" rows="3" placeholder="Write your comment..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Comment</button>
        </form>
    </div>

    <!-- Add Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
