<?php
include '../../includes/db_connect.php';
include '../../src/Course.php'; // Include the Course class file

// Start session
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Only allow access to admins
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../authentication/login.php");
    exit();
}

// Check if year, season, and subject are set
if (isset($_GET['year']) && isset($_GET['season']) && isset($_GET['subject'])) {
    $year = $_GET['year']; // Make sure to validate and sanitize this input
    $season = $_GET['season']; // Make sure to validate and sanitize this input
    $subject = $_GET['subject']; // Make sure to validate and sanitize this input

    $query = "SELECT course_id FROM Courses WHERE year = ? AND season = ? AND course_subject = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $year, $season, $subject); // year is integer, season and subject are strings
    $stmt->execute();
    $result = $stmt->get_result();

    $courses = [];
    while ($row = $result->fetch_assoc()) {
        $course = new Course($row['course_id']); // Instantiate a new Course object for each course id
        $courses[] = [
            'id' => $course->getID(),
            'crn' => $course->getCRN(),
            'courseName' => $course->getCourseName(),
            'instructorName' => $course->getInstructorName(),
            'feedbackCount' => $course->getFeedbackCount(),
            'createdTime' => date("F j, Y, g:i a", strtotime($course->getCreatedTime())),
        ];
    }

    echo json_encode($courses);
    exit();
}
?>
