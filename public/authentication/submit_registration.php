<?php
include '../../includes/db_connect.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Insert into Users table
        $stmt = $conn->prepare('INSERT INTO Users (first_name, last_name, email, password, role, school_id) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('sssssi', $_POST['first_name'], $_POST['last_name'], $_POST['email'], password_hash($_POST['password'], PASSWORD_DEFAULT), $_POST['role'], $_POST['school_id']);
        $stmt->execute();

        // Get the id of the last inserted row (i.e., the registered user)
        $user_id = $conn->insert_id;

        // Start the session
        session_start();

        // Set session variables
        $_SESSION['user_id'] = $user_id;
        $_SESSION['role'] = $_POST['role'];

        // Depending on the role, redirect user to the appropriate dashboard based on their role
        if ($_POST['role'] == 'student') {
            header('Location: ../student/student_dashboard.php');
        } else if ($_POST['role'] == 'instructor') {
            header('Location: ../instructor/instructor_dashboard.php');
        } else if ($_POST['role'] == 'admin') {
            header('Location: ../admin/admin_dashboard.php');
        }
        exit;
    } catch (Exception $e) {
        // Handle error (you should do this in a better way in your production code)
        die('Registration failed: ' . $e->getMessage());
    }
}
?>
