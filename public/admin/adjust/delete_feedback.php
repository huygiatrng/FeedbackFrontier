<?php
include '../../../includes/db_connect.php';
include '../../../src/Feedback.php';

// Start session
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Only allow access to admins
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../../authentication/login.php");
    exit();
}

if(isset($_GET['feedback_id'])){
    try {
        Feedback::deleteFeedback($_GET['feedback_id']);
        echo 1; // Successfully deleted
    } catch (\Exception $e) {
        echo 0; // Deletion unsuccessful
    }
}else{
    echo 0; // Deletion unsuccessful
}

header("Location: ../manage_feedback.php");
exit();
?>
