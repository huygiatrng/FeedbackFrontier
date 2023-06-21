<?php
include 'db_connect.php';
include 'feedback_questions.php';

$title = 'Course Feedbacks';
ob_start();

// Start session
session_start();

// Check if user_id is set in the session and retrieve it
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user_id is not set in the session
    header("Location: login.php");
    exit();
}
$role = $_SESSION['role'];
$course_id = $_GET['course_id'];

$action = $_SESSION['role'] === 'student' ? 'student_dashboard.php' : 'instructor_dashboard.php';


$stmt = $conn->prepare('SELECT Feedback.first_name, Feedback.last_name, Feedback.rating1, Feedback.rating2, Feedback.rating3, Feedback.rating4, Feedback.rating5, Feedback.rating6, Feedback.rating7, Feedback.feedback_text FROM Feedback WHERE course_id = ?');
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();
$feedbacks = [];
while ($row = $result->fetch_assoc()) {
    $feedbacks[] = $row;
}

?>

<h1>View Feedbacks</h1>
<p>You are viewing feedbacks for the course with ID: <?php echo htmlspecialchars($course_id); ?></p>

<form action="<?php echo $action; ?>" method="post">
    <button type="submit" class="btn btn-primary">Back</button>
</form>

<?php foreach ($feedbacks as $feedback): ?>
    <p>
    <?php foreach ($feedbacks as $feedback): ?>
        <p>
            <?php echo "From: " . htmlspecialchars($feedback['first_name'] . ' ' . $feedback['last_name']); ?><br>
            <?php
            foreach ($feedback_questions as $index => $question) {
                $ratingIndex = 'rating' . ($index + 1);
                echo htmlspecialchars($question) . ": " . $feedback[$ratingIndex] . '<br>';
            }
            ?>
            <?php echo "Comments: " . htmlspecialchars($feedback['feedback_text']); ?>
        </p>
    <?php endforeach; ?>
    </p>
<?php endforeach; ?>

<form action="<?php echo $action; ?>" method="post">
    <button type="submit" class="btn btn-primary">Back</button>
</form>


<?php
$content = ob_get_clean();
include 'base.php';
?>
