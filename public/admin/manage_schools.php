<?php
include '../../includes/db_connect.php';
$title = 'Manage Schools';
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

// Fetch all schools
$result = $conn->query("SELECT * FROM School");

?>

<h1>Manage Schools</h1>

<table>
    <tr>
        <th>ID</th>
        <th>School Name</th>
        <th>Actions</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['school_id']; ?></td>
            <td><?php echo $row['school_name']; ?></td>
            <td><a href="#">Edit</a> | <a href="#">Delete</a></td>
        </tr>
    <?php endwhile; ?>
</table>

<a href="../admin/admin_dashboard.php">Back to Dashboard</a>

<?php
$content = ob_get_clean();
include '../../includes/base.php';
?>
