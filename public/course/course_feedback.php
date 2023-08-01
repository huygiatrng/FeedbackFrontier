<?php
include '../../includes/db_connect.php';
include '../../includes/feedback_questions.php';

$title = 'Course Feedback';
$pageHeading = 'Course Feedback';
ob_start();

// Start session
session_start();

// Check if user_id is set in the session and retrieve it
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user_id is not set in the session
    header("Location: ../authentication/login.php");
    exit();
}

$course_id = $_GET['course_id'];

// not let instructor to submit feedback by point him back to view_feedback
if ($_SESSION['role'] == 'admin') {
    header("Location: ../admin/admin_dashboard.php");
    exit();
}

$stmt = $conn->prepare('SELECT Courses.course_subject, Courses.course_number, Courses.season, Courses.instructor_name, Courses.year FROM Courses WHERE Courses.course_id = ?');
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $course = $result->fetch_assoc();
} else {
    die('No course found with the given ID.');
}
?>
<div class="row mb-3">
    <div class="col-12">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">You are providing feedback for the course <?php echo htmlspecialchars($course['course_subject']); ?> <?php echo htmlspecialchars($course['course_number']); ?> - <?php echo htmlspecialchars($course['season']); ?> <?php echo htmlspecialchars($course['year']); ?> - <?php echo htmlspecialchars($course['instructor_name']); ?></h3>
            </div>
            <div class="card-body">
                <form action="submit_feedback.php" method="post">
                    <div class="row mb-3">
                        <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
                        <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                        <?php foreach ($feedback_questions as $index => $question) : ?>
                            <div class="col-md-6 mb-3">
                                <label for="rating<?php echo $index + 1; ?>" class="form-label"><?php echo htmlspecialchars($question); ?></label>
                                <select name="rating<?php echo $index + 1; ?>" id="rating<?php echo $index + 1; ?>" required class="form-control">
                                    <option value="">Select an option</option>
                                    <option value="1">1 - Very poor</option>
                                    <option value="2">2 - Poor</option>
                                    <option value="3">3 - Average</option>
                                    <option value="4">4 - Good</option>
                                    <option value="5">5 - Excellent</option>
                                </select>
                            </div>
                        <?php endforeach; ?>
                        <div class="col-md-6 mb-3">
                            <label for="feedback_text">Additional Comments:</label>
                            <textarea id="feedback_text" name="feedback_text" class="form-control" rows="3"></textarea><br>
                        </div>
                        <div class="col-12 mb-3 d-flex justify-content-center   ">
<!--                            <div class="form-check">-->
<!--                                <input class="form-check-input" type="checkbox" id="is_anonymous" name="is_anonymous">-->
<!--                                <label class="form-check-label" for="is_anonymous">Check this if you want to submit feedback anonymously.</label>-->
<!--                            </div>-->
                            <div class="form-check custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input form-check-input" id="is_anonymous" name="is_anonymous">
                                <label class="form-check-label custom-control-label" for="is_anonymous">Check this if you want to submit feedback anonymously.</label>
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">Submit Feedback</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-header -->
        </div>
    </div>
</div>

    <a class="btn btn-secondary" href="../user/user_dashboard.php">Back</a>

<?php
$content = ob_get_clean();
include '../../includes/base.php';
?>