<?php
include '../../includes/db_connect.php';
$title = 'Manage Users';
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

// Fetch all students
$students = $conn->query("SELECT * FROM Users WHERE role = 'student'");

// Fetch all instructors and the number of courses they are teaching
$instructors = $conn->query("
    SELECT Users.*, COUNT(Courses.course_id) AS course_count 
    FROM Users 
    LEFT JOIN Courses ON Users.user_id = Courses.instructor_id 
    WHERE role = 'instructor' 
    GROUP BY Users.user_id
");

?>

<h1>Manage Users</h1>

<h2>Students</h2>
<table>
    <tr>
        <th>ID</th>
        <th>First name</th>
        <th>Last name</th>
        <th>Email</th>
        <th>Actions</th>
    </tr>
    <?php while($row = $students->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['user_id']; ?></td>
            <td><?php echo $row['first_name']; ?></td>
            <td><?php echo $row['last_name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td>
                <a href="adjust/edit_user.php?user_id=<?php echo $row['user_id']; ?>">Edit</a> |
                <a href="adjust/delete_user.php?user_id=<?php echo $row['user_id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<h2>Instructors</h2>
<table>
    <tr>
        <th>ID</th>
        <th>First name</th>
        <th>Last name</th>
        <th>Email</th>
        <th>Courses Taught</th>
        <th>Actions</th>
    </tr>
    <?php while($row = $instructors->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['user_id']; ?></td>
            <td><?php echo $row['first_name']; ?></td>
            <td><?php echo $row['last_name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['course_count']; ?></td>
            <td>
                <a href="adjust/edit_user.php?user_id=<?php echo $row['user_id']; ?>">Edit</a> |
                <a href="adjust/delete_user.php?user_id=<?php echo $row['user_id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<a href="../admin/admin_dashboard.php">Back to Dashboard</a>

<?php
$content = ob_get_clean();
include '../../includes/base.php';
?>
