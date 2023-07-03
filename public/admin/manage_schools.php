<?php
include '../../includes/db_connect.php';
include '../../src/School.php';

$title = 'Manage Schools';
$pageHeading = 'Manage Schools';

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

// Fetch all schools using the School class
$schools = School::getAllSchools($conn);
?>
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title ">Schools </h3><a href="adjust/insert_school.php"
                                                            class="float-right btn-sm btn-light text-dark"><i
                                class="fa fa-plus-square" aria-hidden="true"></i> Add New School</a>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive">
                    <table class="datatable table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>School Name</th>
                            <th>Created At</th>
                            <th style="width: 10%;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($schools as $school) : ?>
                            <tr>
                                <td><?php echo $school->getID(); ?></td>
                                <td><?php echo $school->getName(); ?></td>
                                <td><?php echo date("F j, Y, g:i a", strtotime($school->getCreatedAt())); ?></td>
                                <td class="text-nowrap">
                                    <a href="adjust/edit_school.php?school_id=<?php echo $school->getID(); ?>"
                                       class="btn btn-sm btn-warning text-white"> <i class="fas fa-edit    "></i></a> |
                                    <a href="adjust/delete_school.php?school_id=<?php echo $school->getID(); ?>"
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
                "ordering": true,
                "info": true,

            });
        })
    </script>
<?php
$pageJs = ob_get_clean();
include '../../includes/base.php';
?>