<?php
include '../../../includes/db_connect.php';
$title = 'Update Profile';
$pageHeading = 'User Profile';
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
    $user= $result->fetch_assoc();
} else {
    die('No user found with the given ID.');
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
                            Email: <input type="email" name="email" value="<?php echo $user['email']; ?>" class="form-control">
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
<div class="row">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title ">Your Feedbacks</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
                <table class="datatable table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Anonymous</th>
                            <th>CRN</th>
                            <th>Course Name</th>
                            <th>Rating 1</th>
                            <th>Rating 2</th>
                            <th>Rating 3</th>
                            <th>Rating 4</th>
                            <th>Rating 5</th>
                            <th>Rating 6</th>
                            <th>Rating 7</th>
                            <th>Free Text</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch feedback for the user
                        $result = $conn->query("SELECT Feedback.*, Courses.CRN, Courses.course_subject, Courses.course_number FROM Feedback JOIN Courses ON Feedback.course_id = Courses.course_id WHERE Feedback.user_id = $user_id LIMIT 10");
                        while ($row = $result->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo $row['createdAt']; ?></td>
                                <td><?php echo $row['anonymous'] == 1 ? 'Yes' : 'No'; ?></td>
                                <td><?php echo $row['CRN']; ?></td>
                                <td><?php echo "{$row['course_subject']} {$row['course_number']}";?></td>
                                <td><?php echo $row['rating1']; ?></td>
                                <td><?php echo $row['rating2']; ?></td>
                                <td><?php echo $row['rating3']; ?></td>
                                <td><?php echo $row['rating4']; ?></td>
                                <td><?php echo $row['rating5']; ?></td>
                                <td><?php echo $row['rating6']; ?></td>
                                <td><?php echo $row['rating7']; ?></td>
                                <td><?php echo $row['feedback_text']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>

                </table>
            </div>
            <!-- /.card-body -->
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

    function validatePassword() {
        if (password.value != confirm_password.value) {
            confirm_password.setCustomValidity("Passwords don't match");
        } else {
            confirm_password.setCustomValidity('');
        }
    }

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
ob_start();
?>
<script src="../../../assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../../assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../../assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../../assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
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
include '../../../includes/base.php';
?>