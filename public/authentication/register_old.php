<?php
include '../../includes/db_connect.php';
$title = 'Register';

try {
    // Fetch all schools from the database
    $query = $conn->query('SELECT * FROM School');
    $schools = array();
    while ($row = $query->fetch_assoc()) {
        $schools[] = $row;
    }
} catch (Exception $e) {
    die('Database connection failed: ' . $e->getMessage());
}

ob_start();
?>

<h1>Register</h1>
<form action="submit_registration.php" method="post">
    <div class="form-group">
        <label for="role">I am a:</label>
        <select name="role" id="role" class="form-control" required>
            <option value="">Select a Role</option>
            <option value="student">Student</option>
            <option value="instructor">Instructor</option>
        </select>
    </div>
    <div class="form-group">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" class="form-control" minlength="6" required>
    </div>
    <div class="form-group">
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required
               oninput="checkPassword()">
    </div>
    <div class="form-group">
        <label for="school_id">School:</label>
        <select name="school_id" id="school_id" class="form-control" required>
            <option value="">Select a School</option>
            <?php foreach ($schools as $school) : ?>
                <option value="<?php echo $school['school_id']; ?>"><?php echo $school['school_name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Register</button>
</form>

<a href="../index.php" class="btn btn-secondary">Back</a>

<script>
    function checkPassword() {
        var password = document.getElementById('password');
        var confirm_password = document.getElementById('confirm_password');
        if (password.value !== confirm_password.value) {
            confirm_password.setCustomValidity("Passwords do not match");
        } else {
            confirm_password.setCustomValidity('');
        }
    }
</script>

<?php
$content = ob_get_clean();
include '../../includes/base.php';
?>
