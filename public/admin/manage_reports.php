<?php
include '../../includes/db_connect.php';
require_once '../../src/Report.php';
include '../../includes/feedback_questions.php';

$title = 'Manage Reports';
$pageHeading = 'Manage Reports';
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

try {
    // Create a new report instance
    $report = new Report($conn);
    // Fetch all reports
    $reports = $report->getAllReportsWithUserDetails();
} catch (Exception $e) {
    // Add error handling as necessary
    die('Error fetching reports: ' . $e->getMessage());
}

?>
<div class="row">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Reports</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
                <table class="datatable table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Reported User</th>
                        <th>Reporter</th>
                        <th>Feedback ID</th>
                        <th>Reason Option</th>
                        <th>Reason Text</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($reports as $row) : ?>
                        <tr data-widget="expandable-table" aria-expanded="false">
                            <td><?php echo $row['report_id']; ?></td>
                            <td><?php echo $row['reported_first_name'] . ' ' . $row['reported_last_name']; ?></td>
                            <td><?php echo $row['reporter_first_name'] . ' ' . $row['reporter_last_name']; ?></td>
                            <td><?php echo $row['feedback_id']; ?></td>
                            <td><?php echo $row['reason_option']; ?></td>
                            <td><?php echo $row['reason_text']; ?></td>
                            <td><?php echo date("F j, Y, g:i a", strtotime($row['createdAt'])); ?></td>
                            <td class="text-nowrap">
                                <a href="report_decide/delete_feedback_report.php?report_id=<?php echo $row['report_id']; ?>"
                                   onclick="deleteAlert(event);" class="btn btn-sm btn-warning text-white"> <i
                                            class="fa fa-trash"></i></a> |
                                <a href="report_decide/delete_report.php?report_id=<?php echo $row['report_id']; ?>"
                                   onclick="deleteAlert(event);" class="btn btn-sm btn-danger"> <i class="fa fa-trash"
                                                                                                   aria-hidden="true"></i></a>
                            </td>
                        </tr>
                        <tr class="expandable-body">
                            <td colspan="8">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Question</th>
                                        <th>Rating</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($feedback_questions as $index => $question) : ?>
                                        <tr>
                                            <td><?php echo $question; ?></td>
                                            <td><?php echo $row['rating' . ($index + 1)]; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <th>Free form text</th>
                                        <td><?php echo $row['feedback_text']; ?></td>
                                    </tr>
                                    </tbody>
                                </table>
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


<script src="../../assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<?php
$content = ob_get_clean();
ob_start();
?>

<script>
    $(document).ready(function () {
        // Handle row click
        $('tr[data-widget="expandable-table"]').click(function () {
            var $expandableBody = $(this).next('.expandable-body');
            if ($expandableBody.is(':visible')) {
                $expandableBody.hide('fast');
            } else {
                $expandableBody.show('fast');
            }
        });

        // Handle report button click
        $(".report-btn").click(function (event) {
            event.stopPropagation();
            var feedback_id = $(this).data('feedback-id');
            $("#feedback_id").val(feedback_id);
            var course_id = $(this).data('course-id');
            $("#course_id").val(course_id);
            $('#reportModal').modal('show');
        });
    });
</script>


<?php
$pageJs = ob_get_clean();
include '../../includes/base.php';
?>
