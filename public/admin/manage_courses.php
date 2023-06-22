<?php
include '../../includes/db_connect.php';
$title = 'Manage Courses';
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

// Fetch all courses and their respective instructor's first and last name
$result = $conn->query("SELECT Courses.*, Users.first_name, Users.last_name FROM Courses INNER JOIN Users ON Courses.instructor_id=Users.user_id");

?>

<h1>Manage Courses</h1>

<table>
    <tr>
        <th>ID</th>
        <th>CRN</th>
        <th>Course Name</th>
        <th>Instructor ID</th>
        <th>Instructor Name</th>
        <th>Actions</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['course_id']; ?></td>
            <td><?php echo $row['CRN']; ?></td>
            <td><?php echo $row['course_name']; ?></td>
            <td><?php echo $row['instructor_id']; ?></td>
            <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
            <td><a href="#">Edit</a> | <a href="#">Delete</a></td>
        </tr>
    <?php endwhile; ?>
</table>

<a href="../admin/admin_dashboard.php">Back to Dashboard</a>

<?php
$content = ob_get_clean();
include '../../includes/base.php';
?>
