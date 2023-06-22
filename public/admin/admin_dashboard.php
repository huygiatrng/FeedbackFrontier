<?php
include '../../includes/db_connect.php';
$title = 'Admin Dashboard';
ob_start();

// Start session
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Only allow access to admins
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../authentication/login.php");
    exit();
}

// Fetch some statistics
$userCount = $conn->query("SELECT COUNT(*) FROM Users")->fetch_row()[0];
$courseCount = $conn->query("SELECT COUNT(*) FROM Courses")->fetch_row()[0];
$schoolCount = $conn->query("SELECT COUNT(*) FROM School")->fetch_row()[0];
$feedbackCount = $conn->query("SELECT COUNT(*) FROM Feedback")->fetch_row()[0];
?>

<h1>Welcome, Admin</h1>
<p>Here is the overview of the system:</p>

<table>
    <tr>
        <td>Total Users:</td>
        <td><?php echo $userCount; ?></td>
    </tr>
    <tr>
        <td>Total Courses:</td>
        <td><?php echo $courseCount; ?></td>
    </tr>
    <tr>
        <td>Total Schools:</td>
        <td><?php echo $schoolCount; ?></td>
    </tr>
    <tr>
        <td>Total Feedback:</td>
        <td><?php echo $feedbackCount; ?></td>
    </tr>
</table>

<p>Access management:</p>
<ul>
    <li><a href="manage_users.php">Manage Users</a></li>
    <li><a href="manage_courses.php">Manage Courses</a></li>
    <li><a href="manage_schools.php">Manage Schools</a></li>
    <li><a href="manage_feedback.php">Manage Feedback</a></li>
</ul>

<form action="../authentication/logout.php" method="post">
    <button type="submit" class="btn btn-primary">Logout</button>
</form>

<?php
$content = ob_get_clean();
include '../../includes/base.php';
?>
