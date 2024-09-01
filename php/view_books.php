<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php';

// Get approved books
$result = $conn->query("SELECT * FROM ebooks WHERE status = 'approved'");

if (!$result) {
    die("Database query failed: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Books</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Add Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1>View Books</h1>
        <div class="list-group">
            <?php while ($row = $result->fetch_assoc()) : ?>
                <a href="view_book.php?id=<?php echo $row['id']; ?>" class="list-group-item list-group-item-action">
                    <h5 class="mb-1"><?php echo htmlspecialchars($row['title']); ?></h5>
                    <small>Published on: <?php echo htmlspecialchars($row['created_at']); ?></small>
                </a>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Add Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
