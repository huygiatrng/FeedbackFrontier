<?php
require_once '../../../includes/db_connect.php';
require_once '../../../src/User.php';

$title = 'Edit User';
$pageHeading = 'Edit User';
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

// Fetch user information
if (isset($_GET['user_id'])) {
    try {
        $user = User::getUserById($_GET['user_id']);
    } catch (Exception $e) {
        // Redirect back to manage users if user id is invalid
        header("Location: ../manage_users.php");
        exit();
    }
} else {
    // Redirect back to manage users if no user id is provided
    header("Location: ../manage_users.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $newEmail = $_POST['email'];
        echo "New email before change: " . $newEmail;

        $user->changeEmail($newEmail);

        echo "  Email after change: " . $user->getEmail();

        $user->save();

        // Redirect back to manage users
        header("Location: ../manage_users.php");
        exit();
    } catch (Exception $e) {
        // Handle exception (e.g., display error message)
        echo "  Error: " . $e->getMessage();
    }
}



?>

<div class="row mb-3">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Edit User</h3>
            </div>
            <div class="card-body">
                <form action="" method="post">
                    <div class="row mb-3">
                        <div class="col-xl-3 col-md-6 mb-3">
                            <label for="first_name">First Name</label>
                            <input type="text" id="first_name" name="first_name" value="<?php echo $user->first_name; ?>" class="form-control">
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <label for="last_name">Last Name</label>
                            <input type="text" id="last_name" name="last_name" value="<?php echo $user->last_name; ?>" class="form-control">
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?php echo $user->email; ?>" class="form-control">
                        </div>
                        <div class="col-xl-3 col-md-6 d-flex align-items-end mb-3">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                        <div class="col-12">
                            <?php
                            if (isset($_SESSION['message'])) {
                                echo '<p class="text-success">' . $_SESSION['message'] . '.</p>';
                                unset($_SESSION['message']);
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

<a class="btn btn-secondary" href="../manage_users.php">Back to Users</a>


<?php
$content = ob_get_clean();
include '../../../includes/base.php';
?>
