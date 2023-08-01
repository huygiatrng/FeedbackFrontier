<?php
include '../../../includes/db_connect.php';

// Start session
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Only allow access to admins
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../../authentication/login.php");
    exit();
}

if(isset($_GET['feedback_id'])){
    $feedback_id = $_GET['feedback_id'];

    // Prepare the statements
    $stmt1 = $conn->prepare("DELETE FROM report WHERE feedback_id = ?");
    $stmt2 = $conn->prepare("DELETE FROM feedback WHERE feedback_id = ?");

    // Bind parameters
    $stmt1->bind_param("i", $feedback_id);
    $stmt2->bind_param("i", $feedback_id);

    // Execute queries
    if($stmt1->execute() && $stmt2->execute()){
        echo 1; // Successfully deleted
    }else{
        echo 0; // Deletion unsuccessful
    }
}else{
    echo 0; // Deletion unsuccessful
}
?>
