<?php
include '../../../includes/db_connect.php';
include '../../../src/User.php';

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
    header("Location: user_profile_update.php");
    exit();
}

// Validate password
if (strlen($password) < 6) {
    $_SESSION['message'] = "Password must be at least 6 characters long";
    header("Location: user_profile_update.php");
    exit();
}

// Hash the password before storing
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    // Update user profile
    $stmt = $conn->prepare('UPDATE Users SET email = ?, password = ? WHERE user_id = ?');
    $stmt->bind_param("ssi", $email, $hashed_password, $user_id);
    if ($stmt->execute()) {
        // Update success message
        $_SESSION['message'] = "Profile updated successfully";
    } else {
        throw new \Exception("SQL execution error: " . $stmt->error);
    }
} catch (\Exception $e) {
    // Handle the exception, e.g., log the error or display an error message to the user
    $_SESSION['message'] = "Your email is not available to use";
}

header("Location: user_profile_update.php");
exit();

?>
