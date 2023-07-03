<?php
include '../../../includes/db_connect.php';
include '../../../includes/feedback_questions.php';
include '../../../src/Feedback.php';

$title = 'Edit Feedback';
$pageHeading = 'Edit Feedback';
ob_start();

// Start session
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Only allow access to admins
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../../authentication/login.php");
    exit();
}

$feedback_id = $_GET['feedback_id'];
$feedback = Feedback::getFeedbackById($feedback_id);

// If form submitted, update the feedback
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $anonymous = isset($_POST['anonymous']) ? 1 : 0;
    $rating1 = $_POST['rating1'];
    $rating2 = $_POST['rating2'];
    $rating3 = $_POST['rating3'];
    $rating4 = $_POST['rating4'];
    $rating5 = $_POST['rating5'];
    $rating6 = $_POST['rating6'];
    $rating7 = $_POST['rating7'];
    $feedback_text = $_POST['feedback_text'];

    Feedback::updateFeedback($feedback_id, $rating1, $rating2, $rating3, $rating4, $rating5, $rating6, $rating7, $feedback_text, $anonymous);
    header("Location: ../manage_feedback.php");
    exit();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


?>


    <div class="row mb-3">
        <div class="col-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <div class="row mb-3">
                            <input type="hidden" name="course_id" value="<?php echo $feedback->getCourseId(); ?>">
                            <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                            <?php foreach ($feedback_questions as $index => $question) : $j = $index + 1; ?>
                                <div class="col-md-6 mb-3">
                                    <label for="rating<?php echo $j; ?>"
                                           class="form-label"><?php echo htmlspecialchars($question); ?></label>
                                    <select name="rating<?php echo $j; ?>" id="rating<?php echo $j; ?>" required
                                            class="form-control">
                                        <option value="">Select an option</option>
                                        <option value="1" <?php echo ($feedback->{"getRating$j"}() == 1) ? " selected" : "" ?>>1 - Very poor</option>
                                        <option value="2" <?php echo ($feedback->{"getRating$j"}() == 2) ? " selected" : "" ?>>2 - Poor</option>
                                        <option value="3" <?php echo ($feedback->{"getRating$j"}() == 3) ? " selected" : "" ?>>3 - Average</option>
                                        <option value="4" <?php echo ($feedback->{"getRating$j"}() == 4) ? " selected" : "" ?>>4 - Good</option>
                                        <option value="5" <?php echo ($feedback->{"getRating$j"}() == 5) ? " selected" : "" ?>>5 - Exelent</option>
                                    </select>
                                </div>
                            <?php endforeach; ?>
                            <div class="col-md-6 mb-3">
                                <label for="feedback_text">Additional Comments:</label>
                                <textarea id="feedback_text" name="feedback_text" class="form-control"
                                          rows="3"><?php echo $feedback->getFeedbackText(); ?></textarea><br>
                            </div>
                            <div class="col-12 mb-3 d-flex justify-content-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_anonymous" name="anonymous"
                                           value="1" <?php if ($feedback->isAnonymous()) echo 'checked'; ?>>
                                    <label class="form-check-label" for="is_anonymous">Check this if you want to submit
                                        feedback anonymously.</label>
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

    <a class="btn btn-secondary" href="../manage_feedback.php">Back to Feedbacks</a>


<?php
$content = ob_get_clean();
include '../../../includes/base.php';
?>