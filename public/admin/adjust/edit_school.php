<?php
include '../../../includes/db_connect.php';
include '../../../src/School.php';  // Include School class

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

// Fetch school from DB using static method of School class
$school = School::getSchoolById($school_id, $conn);

// If form submitted, update the database
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $school_name = $_POST['school_name'];

    // Call the method to change the school name
    $school->changeName($school_name);

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
                            <input type="text" name="school_name" value="<?php echo $school->getName(); ?>" required class="form-control">
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

<a class="btn btn-secondary" href="../manage_schools.php">Back to Schools</a>


<?php
$content = ob_get_clean();
include '../../../includes/base.php';
?>
