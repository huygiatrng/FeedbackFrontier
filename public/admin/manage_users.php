<?php
include '../../includes/db_connect.php';
require_once '../../src/User.php';
$title = 'Manage Users';
$pageHeading = 'Manage Users';
ob_start();

// Start session
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Only allow access to admins
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../authentication/login.php");
    exit();
}

// Fetch all students
$students = User::getStudents($conn);

?>

<div class="row">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Users</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Feedbacks</th>
                        <th>Reports</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?php echo $student->user_id; ?></td>
                            <td><?php echo $student->first_name; ?></td>
                            <td><?php echo $student->last_name; ?></td>
                            <td><?php echo $student->email; ?></td>
                            <td><?php echo count($student->getFeedbacks()); ?></td>
                            <td><?php echo count($student->getReports()); ?></td>
                            <td><?php echo date("F j, Y, g:i a", strtotime($student->createdAt)); ?></td>
                            <td class="text-nowrap">
                                <a href="adjust/edit_user.php?user_id=<?php echo $student->user_id; ?>"
                                   class="btn btn-sm btn-warning text-white"> <i class="fas fa-edit"></i></a> |
                                <a href="adjust/delete_user.php?user_id=<?php echo $student->user_id; ?>"
                                   onclick="deleteAlert(event);" class="btn btn-sm btn-danger"> <i class="fa fa-trash" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>

<a class="btn btn-secondary" href="../admin/admin_dashboard.php">Back to Dashboard</a>

<?php
$content = ob_get_clean();
ob_start();
?>

<script src="../../assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script>
    $(document).ready(function () {
        $('.table').DataTable();
    });
</script>

<?php
$pageJs = ob_get_clean();
include '../../includes/base.php';
?>
