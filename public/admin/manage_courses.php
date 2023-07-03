<?php
include '../../includes/db_connect.php';
include '../../src/Course.php'; // Include the Course class file

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
$result = $conn->query("SELECT course_id FROM Courses");
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
                        <?php while ($row = $result->fetch_assoc()) :
                            $course = new Course($row['course_id']); // Instantiate a new Course object for each course id
                            ?>
                            <tr>
                                <td><?php echo $course->getID(); ?></td>
                                <td><?php echo $course->getCRN(); ?></td>
                                <td><?php echo $course->getCourseName(); ?></td>
                                <td><?php echo $course->getInstructorName(); ?></td> <!-- Adjust this if you want to show instructor's full name instead of id -->
                                <td class="text-center">
                                    <a href="view/view_feedback_of_course.php?course_id=<?php echo $course->getID(); ?>">
                                        <?php echo $course->getFeedbackCount(); ?>
                                    </a>
                                </td>
                                <td><?php echo date("F j, Y, g:i a", strtotime($course->getCreatedTime())); ?></td>
                                <td class="text-nowrap">
                                    <a href="adjust/edit_course.php?course_id=<?php echo $course->getID(); ?>"
                                       class="btn btn-sm btn-warning text-white"> <i class="fas fa-edit    "></i></a> |
                                    <a href="adjust/delete_course.php?course_id=<?php echo $course->getID(); ?>"
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