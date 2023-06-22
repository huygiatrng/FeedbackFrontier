<?php
include '../../../includes/db_connect.php';
$title = 'Edit User';
ob_start();

// Start session
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Only allow access to admins
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../../authentication/login.php");
    exit();
}

// Fetch user information
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $result = $conn->query("SELECT * FROM Users WHERE user_id = '$user_id'");
    $user = $result->fetch_assoc();
} else {
    // Redirect back to manage users if no user id is provided
    header("Location: ../manage_users.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];

    // Update user
    $conn->query("UPDATE Users SET first_name = '$first_name', last_name = '$last_name', email = '$email' WHERE user_id = '$user_id'");

    // Redirect back to manage users
    header("Location: ../manage_users.php");
    exit();
}
?>

<h1>Edit User</h1>

<form method="POST">
    <label for="first_name">First Name</label>
    <input type="text" id="first_name" name="first_name" value="<?php echo $user['first_name']; ?>">

    <label for="last_name">Last Name</label>
    <input type="text" id="last_name" name="last_name" value="<?php echo $user['last_name']; ?>">

    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>">

    <button type="submit">Save Changes</button>
</form>

<a href="../manage_users.php">Back to Manage Users</a>

<?php
$content = ob_get_clean();
include '../../../includes/base.php';
?>
