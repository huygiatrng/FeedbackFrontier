<?php
include '../../includes/db_connect.php';

// Include the Feedback class file
require_once '../../src/Feedback.php';

// Start session
session_start();

// Check if user_id is set in the session and retrieve it
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user_id is not set in the session
    header("Location: ../authentication/login.php");
    exit();
}

// Get values from POST request
$course_id = $_POST['course_id'];
$user_id = $_POST['user_id'];
$rating1 = $_POST['rating1'];
$rating2 = $_POST['rating2'];
$rating3 = $_POST['rating3'];
$rating4 = $_POST['rating4'];
$rating5 = $_POST['rating5'];
$rating6 = $_POST['rating6'];
$rating7 = $_POST['rating7'];
$feedback_text = $_POST['feedback_text'];
$is_anonymous = isset($_POST['is_anonymous']) ? 1 : 0; // convert boolean to int (1 or 0)

try {
    Feedback::createFeedback($course_id, $user_id, $is_anonymous, $rating1, $rating2, $rating3, $rating4, $rating5, $rating6, $rating7, $feedback_text);
    header("Location: ../user/user_dashboard.php");
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$conn->close();
?>
