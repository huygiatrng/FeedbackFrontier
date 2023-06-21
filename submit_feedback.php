<?php
include 'db_connect.php';

// Start session
session_start();

// Check if user_id is set in the session and retrieve it
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user_id is not set in the session
    header("Location: login.php");
    exit();
}

// Get values from POST request
$course_id = $_POST['course_id'];
$rating1 = $_POST['rating1'];
$rating2 = $_POST['rating2'];
$rating3 = $_POST['rating3'];
$rating4 = $_POST['rating4'];
$rating5 = $_POST['rating5'];
$rating6 = $_POST['rating6'];
$rating7 = $_POST['rating7'];
$feedback_text = $_POST['feedback_text'];
$is_anonymous = isset($_POST['is_anonymous']);

// Determine author's name
$first_name = "";
$last_name = "";
if ($is_anonymous) {
    $first_name = "Anonymous";
    $last_name = "Author";
} else {
    $stmt = $conn->prepare('SELECT first_name, last_name FROM Users WHERE user_id = ?');
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $first_name = $user['first_name'];
        $last_name = $user['last_name'];
    }
}

// Prepare SQL query to insert feedback
$stmt = $conn->prepare('INSERT INTO Feedback (course_id, first_name, last_name, rating1, rating2, rating3, rating4, rating5, rating6, rating7, feedback_text) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
$stmt->bind_param("issiiiiiiis", $course_id, $first_name, $last_name, $rating1, $rating2, $rating3, $rating4, $rating5, $rating6, $rating7, $feedback_text);


// Execute the SQL query
if ($stmt->execute()) {
    header("Location: student_dashboard.php");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
