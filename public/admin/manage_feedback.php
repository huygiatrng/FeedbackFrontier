<?php
include '../../includes/db_connect.php';
$title = 'Manage Feedback';
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

// Fetch all feedback
$result = $conn->query("SELECT * FROM Feedback");

?>

<h1>Manage Feedback</h1>

<table>
    <tr>
        <th>ID</th>
        <th>First name</th>
        <th>Last name</th>
        <th>Course ID</th>
        <th>Q1</th>
        <th>Q2</th>
        <th>Q3</th>
        <th>Q4</th>
        <th>Q5</th>
        <th>Q6</th>
        <th>Q7</th>
        <th>Feedback Text</th>
        <th>Actions</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['feedback_id']; ?></td>
            <td><?php echo $row['first_name']; ?></td>
            <td><?php echo $row['last_name']; ?></td>
            <td><?php echo $row['course_id']; ?></td>
            <td><?php echo $row['rating1']; ?></td>
            <td><?php echo $row['rating2']; ?></td>
            <td><?php echo $row['rating3']; ?></td>
            <td><?php echo $row['rating4']; ?></td>
            <td><?php echo $row['rating5']; ?></td>
            <td><?php echo $row['rating6']; ?></td>
            <td><?php echo $row['rating7']; ?></td>
            <td><?php echo $row['feedback_text']; ?></td>
            <td><a href="#">Edit</a> | <a href="#">Delete</a></td>
        </tr>
    <?php endwhile; ?>
</table>

<a href="../admin/admin_dashboard.php">Back to Dashboard</a>

<?php
$content = ob_get_clean();
include '../../includes/base.php';
?>
