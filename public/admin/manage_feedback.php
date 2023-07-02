<?php
include '../../includes/db_connect.php';
include '../../src/Feedback.php';
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

// Fetch all feedback using the static method of Feedback class
$feedbacks = Feedback::getAllFeedbacks();

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
                        <?php foreach ($feedbacks as $feedback) : ?>
                            <tr>
                                <td><?php echo $feedback->getFeedbackId(); ?></td>
                                <td><?php echo $feedback->first_name; ?></td>
                                <td><?php echo $feedback->last_name; ?></td>
                                <td><?php echo $feedback->getCourseId(); ?></td>
                                <td><?php echo $feedback->isAnonymous() ? 'Yes' : 'No'; ?></td>
                                <td><?php echo $feedback->getRating1(); ?></td>
                                <td><?php echo $feedback->getRating2(); ?></td>
                                <td><?php echo $feedback->getRating3(); ?></td>
                                <td><?php echo $feedback->getRating4(); ?></td>
                                <td><?php echo $feedback->getRating5(); ?></td>
                                <td><?php echo $feedback->getRating6(); ?></td>
                                <td><?php echo $feedback->getRating7(); ?></td>
                                <td><?php echo $feedback->getFeedbackText(); ?></td>
                                <td><?php echo date("F j, Y, g:i a", strtotime($feedback->getCreatedAt())); ?></td>
                                <td class="text-nowrap">
                                    <a href="adjust/edit_feedback.php?feedback_id=<?php echo $feedback->getFeedbackId(); ?>"
                                       class="btn btn-sm btn-warning text-white"> <i class="fas fa-edit"></i></a> |
                                    <a href="adjust/delete_feedback.php?feedback_id=<?php echo $feedback->getFeedbackId(); ?>"
                                       onclick="deleteAlert(event);" class="btn btn-sm btn-danger"> <i
                                                class="fa fa-trash" aria-hidden="true"></i></a>
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