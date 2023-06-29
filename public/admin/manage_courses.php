<?php
include '../../includes/db_connect.php';
$title = 'Manage Courses';
$pageHeading = 'Manage Courses';
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

// Fetch all courses and their respective instructor's first and last name
$result = $conn->query("SELECT Courses.*, Users.first_name, Users.last_name, (SELECT COUNT(*) FROM Feedback WHERE course_id = Courses.course_id) AS feedback_count FROM Courses INNER JOIN Users ON Courses.instructor_id=Users.user_id");
?>
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title ">All Courses </h3><a href="adjust/add_course.php"
                                                                class="float-right btn-sm btn-light text-dark"><i
                                class="fa fa-plus-square" aria-hidden="true"></i> Add New Course</a>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive">
                    <table class="datatable table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>CRN</th>
                            <th>Course Name</th>
                            <th>Instructor Name</th>
                            <th>Feedback Count</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo $row['course_id']; ?></td>
                                <td><?php echo $row['CRN']; ?></td>
                                <td><?php echo $row['course_name']; ?></td>
                                <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                                <td class="text-center">
                                    <a href="view/view_feedback_of_course.php?course_id=<?php echo $row['course_id']; ?>">
                                        <?php echo $row['feedback_count']; ?>
                                    </a>
                                </td>
                                <td><?php echo date("F j, Y, g:i a", strtotime($row['createdAt'])); ?></td>
                                <td class="text-nowrap">
                                    <a href="adjust/edit_course.php?course_id=<?php echo $row['course_id']; ?>"
                                       class="btn btn-sm btn-warning text-white"> <i class="fas fa-edit    "></i></a> |
                                    <a href="adjust/delete_course.php?course_id=<?php echo $row['course_id']; ?>"
                                       onclick="deleteAlert(event);" class="btn btn-sm btn-danger"> <i
                                                class="fa fa-trash" aria-hidden="true"></i></a>
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
        $(document).ready(function () {
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