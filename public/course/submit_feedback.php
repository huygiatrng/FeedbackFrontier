<?php
include '../../includes/db_connect.php';

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

// Prepare SQL query to insert feedback
$stmt = $conn->prepare('INSERT INTO Feedback (course_id, user_id, anonymous, rating1, rating2, rating3, rating4, rating5, rating6, rating7, feedback_text) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
$stmt->bind_param("iiiiiiiiiss", $course_id, $user_id, $is_anonymous, $rating1, $rating2, $rating3, $rating4, $rating5, $rating6, $rating7, $feedback_text);


// Execute the SQL query
if ($stmt->execute()) {
    header("Location: ../student/student_dashboard.php");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
