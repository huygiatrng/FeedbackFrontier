<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../../../includes/db_connect.php';
require_once '../../../src/User.php';

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

    try {
        $user = User::getUserById($user_id);
        $user->deleteUser();

        // Redirect back to manage users
        header("Location: ../manage_users.php");
        exit();
    } catch (Exception $e) {
        // Handle exception (e.g., display error message)
        echo $e->getMessage();
    }
}
?>
