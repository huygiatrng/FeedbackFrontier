<?php
include '../../../includes/db_connect.php';
include '../../../src/Course.php';

// Start session
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Only allow access to admins
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../../../authentication/login.php");
    exit();
}

// Get the course_id from the URL
$course_id = $_GET['course_id'];

try {
    // Delete the course
    Course::deleteCourse($course_id);

    header("Location: ../manage_courses.php");
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
