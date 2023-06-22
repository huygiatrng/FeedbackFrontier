<?php
$title = 'View Feedback';
ob_start();
?>

<h1>View Feedback for [Course Name]</h1>
<!-- Feedback details will be populated here from the database -->

<?php
$content = ob_get_clean();
include 'base.php';
?>
