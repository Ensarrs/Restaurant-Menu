<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];

    if (!empty($username)) {
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // Assuming 'role' column exists in the database

            if ($user['role'] === 'admin') {
                header("Location: admin_dashboard.php"); // Redirect to admin dashboard
            } else {
                header("Location: menu.php"); // Redirect to menu page for non-admin users
            }
            exit();
        } else {
            $error = "User not found."; // Username does not exist
        }
    } else {
        $error = "Please fill in all the fields."; // Fixed typo in error message
    }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>LOGIN</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
</head>
<body>
    <div class="login-form">
        <div class="text">
            LOGIN
        </div>
        
        <?php if (isset($error)) { echo '<p>' . $error . '</p>'; } ?>
        
        <form action="" method="POST">
            <div class="field">
                <div class="fas fa-user"></div>
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="field">
                <div class="fas fa-lock"></div>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button>LOGIN</button>
            <div class="link">
                Not a member? <a href="register.php">Signup now</a>
            </div>
        </form>
    </div>
</body>
</html>
