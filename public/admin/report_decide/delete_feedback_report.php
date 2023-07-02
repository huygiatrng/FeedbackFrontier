<?php
include '../../../includes/db_connect.php';
include '../../../src/Feedback.php';

// Get the report ID from the request
$report_id = intval($_GET['report_id']);

try {
    // Get the feedback ID associated with the report
    $stmt = $conn->prepare("SELECT feedback_id FROM Report WHERE report_id = ?");
    $stmt->bind_param("i", $report_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $feedback_id = $result->fetch_assoc()['feedback_id'];

    // Delete the feedback and associated reports
    Feedback::deleteFeedbackAndReports($feedback_id);
} catch (Exception $e) {
    // Handle the error, e.g., log it or display an error message to the user
    echo "An error occurred: " . $e->getMessage();
    exit;
}

// Redirect the user back to the manage reports page
header("Location: ../manage_reports.php");

?>
