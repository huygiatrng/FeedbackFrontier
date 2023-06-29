<?php
include '../../../includes/db_connect.php';

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

// Begin a transaction
$conn->begin_transaction();

try {
    // Delete the reports associated with the feedbacks for this course
    $stmt1 = $conn->prepare("DELETE r FROM report r INNER JOIN feedback f ON f.feedback_id = r.feedback_id WHERE f.course_id = ?");
    $stmt1->bind_param("i", $course_id);
    $stmt1->execute();

    // Delete the feedbacks for this course
    $stmt2 = $conn->prepare("DELETE FROM feedback WHERE course_id = ?");
    $stmt2->bind_param("i", $course_id);
    $stmt2->execute();

    // Delete the course
    $stmt3 = $conn->prepare("DELETE FROM Courses WHERE course_id = ?");
    $stmt3->bind_param("i", $course_id);
    $stmt3->execute();

    // If everything is fine, commit the changes
    $conn->commit();

    header("Location: ../manage_courses.php");
} catch (Exception $e) {
    // An error occurred; rollback the changes
    $conn->rollback();

    echo "Error: " . $e->getMessage();
}
?>
