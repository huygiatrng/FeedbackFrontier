<?php
$title = 'Admin Dashboard';
ob_start();
?>

<h1>Welcome, Admin</h1>
<p>Here is the overview of the system.</p>
<!-- System overview and management links -->

<?php
$content = ob_get_clean();
include 'base.php';
?>
