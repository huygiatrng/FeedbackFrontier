<!DOCTYPE html>
<html>
<head>
    <title>Course Feedback Website</title>
    <!-- CSS only -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php

if (!isset($_SESSION)) {
    session_start();
}

$homeURL = "#";
$current_page = basename($_SERVER['SCRIPT_NAME']);

if (isset($_SESSION['role'])) {
    switch ($_SESSION['role']) {
        case 'student':
            $homeURL = "../student/student_dashboard.php";
            break;
        case 'instructor':
            $homeURL = "../instructor/instructor_dashboard.php";
            break;
        case 'admin':
            $homeURL = "../admin/admin_dashboard.php";
            break;
    }
} else {
    if ($current_page == "login.php" || $current_page == "register.php") {
        $homeURL = "../../public/index.php";
    } else {
        $homeURL = "../public/index.php";
    }
}
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="<?php echo $homeURL; ?>">Course Feedback</a>
    <!-- Add links here for various pages -->
</nav>
<div class="container">
    <?php echo $content; ?>
</div>
<!-- JavaScript Bundle with Popper -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
