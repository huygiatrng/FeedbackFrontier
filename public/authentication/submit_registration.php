<?php
include '../../includes/db_connect.php';
include '../../src/User.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Assign the value of 'user' to a variable
        $role = 'user';

        // Insert into Users table
        $stmt = $conn->prepare('INSERT INTO Users (first_name, last_name, email, password, role, school_id) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('sssssi', $_POST['first_name'], $_POST['last_name'], $_POST['email'], password_hash($_POST['password'], PASSWORD_DEFAULT), $role, $_POST['school_id']);
        $stmt->execute();

        // Get the id of the last inserted row (i.e., the registered user)
        $user_id = $stmt->insert_id;

        // Start the session
        session_start();

        // Set session variables
        $_SESSION['user_id'] = $user_id;
        $_SESSION['role'] = $role;

        // Redirect user to the appropriate dashboard based on their role (in this case, assuming a user dashboard)
        header('Location: ../user/user_dashboard.php');
        exit;
    } catch (Exception $e) {
        // Handle error (you should do this in a better way in your production code)
        die('Registration failed: ' . $e->getMessage());
    }
}
?>