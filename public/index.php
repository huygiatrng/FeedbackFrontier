<?php
$title = 'Home';
ob_start();
?>

<h1>Welcome to the Course Feedback Website</h1>
<p>This is a platform for students to give feedback on their courses, for instructors to view feedback, and for administrators to manage the system.</p>
<a href="authentication/login.php">Log In</a> or <a href="authentication/register.php">Register</a>

<?php
$content = ob_get_clean();
include '../includes/base.php';
?>
