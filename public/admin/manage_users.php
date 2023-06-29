<?php
include '../../includes/db_connect.php';
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
<div class="row">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title ">Students </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
                <table class="datatable table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First name</th>
                            <th>Last name</th>
                            <th>Email</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $students->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo $row['user_id']; ?></td>
                                <td><?php echo $row['first_name']; ?></td>
                                <td><?php echo $row['last_name']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo date("F j, Y, g:i a", strtotime($row['createdAt'])); ?></td>
                                <td class="text-nowrap">
                                    <a href="adjust/edit_user.php?user_id=<?php echo $row['user_id']; ?>" class="btn btn-sm btn-warning text-white"> <i class="fas fa-edit    "></i></a> |
                                    <a href="adjust/delete_user.php?user_id=<?php echo $row['user_id']; ?>" onclick="deleteAlert(event);" class="btn btn-sm btn-danger"> <i class="fa fa-trash" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>

                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title ">Instructors </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
                <table class="datatable table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First name</th>
                            <th>Last name</th>
                            <th>Email</th>
                            <th>Courses Taught</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $instructors->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo $row['user_id']; ?></td>
                                <td><?php echo $row['first_name']; ?></td>
                                <td><?php echo $row['last_name']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['course_count']; ?></td>
                                <td><?php echo date("F j, Y, g:i a", strtotime($row['createdAt'])); ?></td>
                                <td>
                                    <a href="adjust/edit_user.php?user_id=<?php echo $row['user_id']; ?>" class="btn btn-sm btn-warning text-white"> <i class="fas fa-edit    "></i></a> |
                                    <a href="adjust/delete_user.php?user_id=<?php echo $row['user_id']; ?>" onclick="deleteAlert(event);" class="btn btn-sm btn-danger"> <i class="fa fa-trash" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
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
    $(document).ready(function() {
        $('.datatable').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,

        });
    })
</script>
<?php
$pageJs = ob_get_clean();
include '../../includes/base.php';
?>