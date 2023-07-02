<?php
include '../../includes/db_connect.php';
include '../../src/User.php';

// Get email and password from POST request
$email = $_POST['email'];
$password = $_POST['password'];

// Fetch user by email
$user = User::getUserByEmail($email, $conn);

if ($user) {
    // Verify password
    if (password_verify($password, $user['password'])) {
        // Start session and store user id and role
        session_start();
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];

        // Redirect user to their respective dashboard
        if ($user['role'] == 'admin') {
            header("Location: ../admin/admin_dashboard.php");
        } elseif ($user['role'] == 'user') {
            header("Location: ../user/user_dashboard.php");
        }
    } else {
        echo "Invalid password.";
    }
} else {
    echo "User not found.";
}
?>
