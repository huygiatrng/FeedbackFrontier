<?php
$title = 'Login';
ob_start();
?>

<h1>Login</h1>
<form action="submit_login.php" method="post">
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
</form>
<p>Don't have an account? <a href="register.php">Register here</a>.</p>
<a href="../index.php" class="btn btn-secondary">Back</a>

<?php
$content = ob_get_clean();
include '../../includes/base.php';
?>
