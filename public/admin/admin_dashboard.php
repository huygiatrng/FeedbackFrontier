<?php
include '../../includes/db_connect.php';
$title = 'Admin Dashboard';
$pageHeading = 'Dashboard';
ob_start();

// Start session
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Only allow access to admins
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../authentication/login.php");
    exit();
}

// Fetch some statistics
$userCount = $conn->query("SELECT COUNT(*) FROM Users")->fetch_row()[0];
$courseCount = $conn->query("SELECT COUNT(*) FROM Courses")->fetch_row()[0];
$schoolCount = $conn->query("SELECT COUNT(*) FROM School")->fetch_row()[0];
$feedbackCount = $conn->query("SELECT COUNT(*) FROM Feedback")->fetch_row()[0];
$reportCount = $conn->query("SELECT COUNT(*) FROM Report")->fetch_row()[0];

?>
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?php echo $userCount ?></h3>
                    <p>Total Users</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="manage_users.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3><?php echo $schoolCount ?></h3>
                    <p>Total Schools</p>
                </div>
                <div class="icon">
                    <i class="fas fa-school    "></i>
                </div>
                <a href="manage_schools.php" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3><?php echo $courseCount ?></h3>
                    <p>Total Courses</p>
                </div>
                <div class="icon">
                    <i class="fa fa-book" aria-hidden="true"></i>
                </div>
                <a href="manage_courses.php" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3><?php echo $feedbackCount ?></h3>
                    <p>Total Feedbacks</p>
                </div>
                <div class="icon">
                    <i class="fa fa-quote-left" aria-hidden="true"></i>
                </div>
                <a href="manage_feedback.php" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3><?php echo $reportCount ?></h3>
                    <p>Total Reports</p>
                </div>
                <div class="icon">
                    <i class="fa fa-flag" aria-hidden="true"></i>
                </div>
                <a href="manage_reports.php" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

<?php
$content = ob_get_clean();
include '../../includes/base.php';
?>