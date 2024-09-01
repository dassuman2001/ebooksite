<?php
session_start();
require 'db.php';

// Ensure user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Handle book approval/rejection/deletion
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = intval($_GET['id']);
    
    if ($action == 'approve' || $action == 'reject') {
        $status = ($action == 'approve') ? 'approved' : 'rejected';
        $stmt = $conn->prepare("UPDATE ebooks SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();
        $stmt->close();
    } elseif ($action == 'delete') {
        $stmt = $conn->prepare("DELETE FROM ebooks WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
    
    header("Location: admin.php");
    exit();
}

// Handle user actions
if (isset($_GET['user_action']) && isset($_GET['user_id'])) {
    $user_action = $_GET['user_action'];
    $user_id = intval($_GET['user_id']);
    
    if ($user_action == 'delete') {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
    } elseif ($user_action == 'edit') {
        // For simplicity, we will just view the user details in this example
        header("Location: edit_user.php?id=$user_id");
        exit();
    }

    header("Location: admin.php");
    exit();
}

// Fetch all pending e-books with author name
$ebooks_query = "
    SELECT ebooks.*, users.name as author_name 
    FROM ebooks 
    JOIN users ON ebooks.author_id = users.id 
    WHERE ebooks.status = 'pending'
";
$ebooks_result = $conn->query($ebooks_query);

// Fetch all users
$users_result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../css/style.css">
    <!-- Add Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-dark text-white p-3">
        <div class="container">
            <h1 class="mb-0">Admin Panel</h1>
            <nav class="mt-2">
                <a href="logout.php" class="btn btn-outline-light">Logout</a>
            </nav>
        </div>
    </header>
    <main class="container mt-5">
        <h2>Pending E-books</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Author Name</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Format</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $ebooks_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['author_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars(substr($row['content'], 0, 50)) . '...'; ?></td>
                    <td><?php echo htmlspecialchars($row['format']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    <td>
                        <a href="?action=approve&id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-success btn-sm">Approve</a>
                        <a href="?action=reject&id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-warning btn-sm">Reject</a>
                        <a href="?action=delete&id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this e-book?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h2>User Management</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $users_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['role']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>

    <!-- Add Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
