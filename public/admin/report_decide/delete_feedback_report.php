<?php
include '../../../includes/db_connect.php';

// Get the report ID from the request
$report_id = intval($_GET['report_id']);

// Start a transaction
$conn->begin_transaction();

try {
    // Get the feedback ID associated with the report
    $stmt = $conn->prepare("SELECT feedback_id FROM Report WHERE report_id = ?");
    $stmt->bind_param("i", $report_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $feedback_id = $result->fetch_assoc()['feedback_id'];

    // Delete all reports associated with the feedback
    $stmt = $conn->prepare("DELETE FROM Report WHERE feedback_id = ?");
    $stmt->bind_param("i", $feedback_id);
    $stmt->execute();

    // Delete the feedback
    $stmt = $conn->prepare("DELETE FROM Feedback WHERE feedback_id = ?");
    $stmt->bind_param("i", $feedback_id);
    $stmt->execute();

    // If we made it this far, commit the changes
    $conn->commit();
} catch (Exception $e) {
    // An error occurred; rollback the changes
    $conn->rollback();
    throw $e;
}

// Redirect the user back to the manage reports page
header("Location: ../manage_reports.php");
?>
