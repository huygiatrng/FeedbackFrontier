<?php
include '../../../includes/db_connect.php';

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../authentication/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$email = $_POST['email'];
$password = $_POST['password'];

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['message'] = "Invalid email format";
    header("Location: instructor_profile_update.php");
    exit();
}

// Validate password
if (strlen($password) < 6) {
    $_SESSION['message'] = "Password must be at least 6 characters long";
    header("Location: instructor_profile_update.php");
    exit();
}

// Hash the password before storing
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare('UPDATE Users SET email = ?, password = ? WHERE user_id = ?');
$stmt->bind_param("ssi", $email, $hashed_password, $user_id);
$stmt->execute();

// Update success message
$_SESSION['message'] = "Profile updated successfully";
header("Location: instructor_profile_update.php");
exit();
?>
