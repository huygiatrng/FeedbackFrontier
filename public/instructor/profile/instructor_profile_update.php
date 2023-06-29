<?php
include '../../../includes/db_connect.php';
$title = 'Update Profile';
$pageHeading = 'Update Profile';
ob_start();

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../authentication/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

$stmt = $conn->prepare('SELECT email FROM Users WHERE user_id = ? AND role = ?');
$stmt->bind_param("is", $user_id, $role);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $instructor = $result->fetch_assoc();
} else {
    die('No instructor found with the given ID.');
}
?>
<div class="row mb-3">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Update Your Profile</h3>
            </div>
            <div class="card-body">
                <form action="update_profile.php" method="post">
                    <div class="row mb-3">
                        <div class="col-xl-3 col-md-6 mb-3">
                            Email: <input type="email" name="email" value="<?php echo $instructor['email']; ?>" class="form-control">
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            Password: <input type="password" id="password" name="password" oninput="validatePasswordLength()" class="form-control">
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            Confirm Password: <input type="password" id="confirm_password" oninput="validatePasswordLength()" class="form-control">
                        </div>
                        <div class="col-xl-3 col-md-6 d-flex align-items-end mb-3">
                            <button type="submit" class="btn btn-info">Update</button>
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


<script>
    var password = document.getElementById("password");
    var confirm_password = document.getElementById("confirm_password");

    function validatePassword() {
        if (password.value != confirm_password.value) {
            confirm_password.setCustomValidity("Passwords don't match");
        } else {
            confirm_password.setCustomValidity('');
        }
    }

    password.onchange = validatePassword;
    confirm_password.onkeyup = validatePassword;
</script>

<script>
    var password = document.getElementById("password");
    var confirm_password = document.getElementById("confirm_password");

    function validatePasswordLength() {
        if (password.value.length < 6) {
            password.setCustomValidity("Password must be at least 6 characters long");
        } else {
            password.setCustomValidity('');
        }
        validatePassword();
    }

    password.onchange = validatePasswordLength;
    confirm_password.onkeyup = validatePasswordLength;
</script>

<?php
$content = ob_get_clean();
include '../../../includes/base.php';
?>