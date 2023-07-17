<?php
require_once '../../../includes/db_connect.php';
require_once '../../../src/Report.php';

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

try {
    // $pdo is from your db_connect.php
    $report = new Report($pdo);
    if ($report->deleteReport($report_id)) {
        // Redirect to manage reports page with success message
        $_SESSION['message'] = "Report deleted successfully!";
        $_SESSION['message_type'] = 'success';
    }
} catch (Exception $e) {
    $_SESSION['message'] = "Error: " . $e->getMessage();
    $_SESSION['message_type'] = 'danger';
}

header("Location: ../manage_reports.php");
exit();
?>
