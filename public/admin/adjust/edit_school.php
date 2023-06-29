<?php
include '../../../includes/db_connect.php';
$title = 'Edit School';
$pageHeading = 'Edit School';
ob_start();

// Start session
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Only allow access to admins
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../../authentication/login.php");
    exit();
}

$school_id = $_GET['school_id'];
$result = $conn->query("SELECT * FROM School WHERE school_id = $school_id");

$row = $result->fetch_assoc();

// If form submitted, update the database
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $school_name = $_POST['school_name'];
    $sql = "UPDATE School SET school_name = '$school_name' WHERE school_id = $school_id";
    $conn->query($sql);

    header("Location: ../manage_schools.php");
    exit();
}

?>
<div class="row mb-3">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Edit School</h3>
            </div>
            <div class="card-body">
                <form action="" method="post">
                    <div class="row mb-3">
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="school_name" class="form-label">School Name:</label>
                            <input type="text" name="school_name" value="<?php echo $row['school_name']; ?>" required class="form-control">
                        </div>

                        <div class="col-xl-3 col-md-6 d-flex align-items-end mb-3">
                            <button type="submit" class="btn btn-info">Save changes</button>
                        </div>
                        <div class="col-12">
                            <?php
                            if (isset($error)) {
                                echo '<p class="text-danger">' . $error . '.</p>';
                            }
                            ?>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-header -->
        </div>
    </div>
</div>


<?php
$content = ob_get_clean();
include '../../../includes/base.php';
?>