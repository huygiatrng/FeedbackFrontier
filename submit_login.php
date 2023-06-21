<?php
include 'db_connect.php';

// Get email and password from POST request
$email = $_POST['email'];
$password = $_POST['password'];

// Prepare SQL query
$stmt = $conn->prepare("SELECT * FROM Users WHERE email = ?");
$stmt->bind_param("s", $email);

// Execute the SQL query
$stmt->execute();
$result = $stmt->get_result();

// If a record is found
if($result->num_rows > 0){
    $user = $result->fetch_assoc();

    // Verify password
    if(password_verify($password, $user['password'])){
        // Start session and store user id and role
        session_start();
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];

        // Redirect user to their respective dashboard
        if($user['role'] == 'admin'){
            header("Location: admin_dashboard.php");
        }elseif($user['role'] == 'student'){
            header("Location: student_dashboard.php");
        }elseif($user['role'] == 'instructor'){
            header("Location: instructor_dashboard.php");
        }
    }else{
        echo "Invalid password.";
    }
}else{
    echo "User not found.";
}

$stmt->close();
$conn->close();
?>
