<?php
include '../../includes/db_connect.php';
session_start();

// Check if user_id is set in the session and retrieve it
if (!isset($_SESSION['user_id'])) {
    // Send a JSON error response
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $feedback_id = $_POST['feedback_id'];
    $reason_option = $_POST['reason_option'];
    $reason_text = $_POST['reason_text'];
    $course_id = $_POST['course_id'];

    $stmt = $conn->prepare('INSERT INTO Report (user_id, feedback_id, reason_option, reason_text) VALUES (?, ?, ?, ?)');
    $stmt->bind_param("iiss", $user_id, $feedback_id, $reason_option, $reason_text);

    if ($stmt->execute()) {
        // Return a success message
        echo json_encode(['success' => 'Successfully reported!']);
        exit();
    } else {
        // Handle error
        echo json_encode(['error' => 'Error: ' . $stmt->error]);
        exit();
    }
} else {
    // Not a POST request, return an error
    echo json_encode(['error' => 'Invalid request']);
    exit();
}
?>
