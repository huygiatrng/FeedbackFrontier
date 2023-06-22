<?php
include '../../../includes/db_connect.php';
$title = 'Delete User';

// Start session
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Only allow access to admins
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../../authentication/login.php");
    exit();
}

// Delete user
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $conn->query("DELETE FROM Users WHERE user_id = '$user_id'");
}

// Redirect back to manage users
header("Location: ../manage_users.php");
exit();
