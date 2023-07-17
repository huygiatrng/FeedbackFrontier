<?php
include '../../../includes/db_connect.php';
include '../../../src/School.php';

// Start session
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Only allow access to admins
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../../authentication/login.php");
    exit();
}

$school_id = $_GET['school_id'];

try {
    School::deleteSchoolById($school_id, $conn);

    header("Location: ../manage_schools.php");
    exit();
} catch (\Exception $e) {
    // Handle the exception, e.g., log the error or display an error message to the user
    echo "An error occurred: " . $e->getMessage();
    exit();
}

?>
