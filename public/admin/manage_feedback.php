<?php
include '../../includes/db_connect.php';
$title = 'Manage Feedback';
$pageHeading = 'Manage Feedback';
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

// Fetch all feedback
$result = $conn->query("SELECT Feedback.*, Users.first_name, Users.last_name FROM Feedback JOIN Users ON Feedback.user_id = Users.user_id");

?>
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title ">Feedbacks </h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive">
                    <table class="datatable table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>First name</th>
                            <th>Last name</th>
                            <th>Course ID</th>
                            <th>Anonymous</th>
                            <th>Q1</th>
                            <th>Q2</th>
                            <th>Q3</th>
                            <th>Q4</th>
                            <th>Q5</th>
                            <th>Q6</th>
                            <th>Q7</th>
                            <th>Feedback Text</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo $row['feedback_id']; ?></td>
                                <td><?php echo $row['first_name']; ?></td>
                                <td><?php echo $row['last_name']; ?></td>
                                <td><?php echo $row['course_id']; ?></td>
                                <td><?php echo $row['anonymous'] ? 'Yes' : 'No'; ?></td>
                                <td><?php echo $row['rating1']; ?></td>
                                <td><?php echo $row['rating2']; ?></td>
                                <td><?php echo $row['rating3']; ?></td>
                                <td><?php echo $row['rating4']; ?></td>
                                <td><?php echo $row['rating5']; ?></td>
                                <td><?php echo $row['rating6']; ?></td>
                                <td><?php echo $row['rating7']; ?></td>
                                <td><?php echo $row['feedback_text']; ?></td>
                                <td><?php echo date("F j, Y, g:i a", strtotime($row['createdAt'])); ?></td>
                                <td class="text-nowrap">
                                    <a href="adjust/edit_feedback.php?feedback_id=<?php echo $row['feedback_id']; ?>"
                                       class="btn btn-sm btn-warning text-white"> <i class="fas fa-edit    "></i></a> |
                                    <a href="adjust/delete_feedback.php?feedback_id=<?php echo $row['feedback_id']; ?>"
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
                "autoWidth": true,
                "ordering": true,
                "info": true,

            });
        })
    </script>
<?php
$pageJs = ob_get_clean();
include '../../includes/base.php';
?>