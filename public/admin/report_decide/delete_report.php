<?php
include '../../../includes/db_connect.php';

// Start session
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Only allow access to admins
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../authentication/login.php");
    exit();
}

// Get report id from URL
if (isset($_GET['report_id'])) {
    $report_id = $_GET['report_id'];
} else {
    // Redirect to manage reports page if report id is not provided
    header("Location: ../manage_reports.php");
    exit();
}

// Delete report
$sql = "DELETE FROM Report WHERE report_id = ?";

// Prepare statement
if ($stmt = $conn->prepare($sql)) {
    // Bind variables to the prepared statement as parameters
    $stmt->bind_param("i", $report_id);

    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        // Redirect to manage reports page with success message
        $_SESSION['message'] = "Report deleted successfully!";
        $_SESSION['message_type'] = 'success';
        header("Location: ../manage_reports.php");
        exit();
    } else {
        // Redirect to manage reports page with error message
        $_SESSION['message'] = "Error: Could not delete report. Please try again.";
        $_SESSION['message_type'] = 'danger';
        header("Location: ../manage_reports.php");
        exit();
    }
} else {
    // Redirect to manage reports page with error message
    $_SESSION['message'] = "Error: Could not prepare statement. Please try again.";
    $_SESSION['message_type'] = 'danger';
    header("Location: ../manage_reports.php");
    exit();
}

$stmt->close();
$conn->close();
?>
