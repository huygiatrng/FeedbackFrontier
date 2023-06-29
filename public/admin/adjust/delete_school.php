<?php
include '../../../includes/db_connect.php';

// Start session
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Only allow access to admins
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../../authentication/login.php");
    exit();
}

$school_id = $_GET['school_id'];

$conn->query("DELETE FROM School WHERE school_id = $school_id");

header("Location: ../manage_schools.php");
exit();
?>
