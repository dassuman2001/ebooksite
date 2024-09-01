<?php
session_start();
require 'php/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $role);
    $stmt->execute();
    $stmt->close();

    // Log in the user after registration
    $_SESSION['user_id'] = $conn->insert_id;
    $_SESSION['role'] = $role;

    if ($role == 'admin') {
        header("Location: php/admin.php");
    } else {
        header("Location: php/dashboard.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Add Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- New Registration Design -->
    <section class="vh-100 gradient-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-dark text-white" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">
                            <div class="mb-md-5 mt-md-4 pb-5">

                                <h2 class="fw-bold mb-2 text-uppercase">Register</h2>
                                <p class="text-white-50 mb-5">Please fill in the registration form!</p>

                                <!-- Registration form -->
                                <form action="register.php" method="POST">
                                    <div class="form-outline form-white mb-4">
                                        <input type="text" name="name" class="form-control form-control-lg" placeholder="Name" required />
                                    </div>

                                    <div class="form-outline form-white mb-4">
                                        <input type="email" name="email" class="form-control form-control-lg" placeholder="Email" required />
                                    </div>

                                    <div class="form-outline form-white mb-4">
                                        <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" required />
                                    </div>

                                    <div class="form-outline form-white mb-4">
                                        <select name="role" class="form-select form-control-lg" required>
                                            <option value="user">User</option>
                                            <option value="author">Author</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                    </div>

                                    <button class="btn btn-outline-light btn-lg px-5" type="submit">Register</button>
                                </form>
                            </div>

                            <div>
                                <p class="mb-0">Already have an account? 
                                    <a href="index.php" class="text-white-50 fw-bold">Login</a>
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Add Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
