<?php
include 'db_connect.php';
include 'feedback_questions.php';

$title = 'Course Feedback';
ob_start();

// Start session
session_start();

// Check if user_id is set in the session and retrieve it
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user_id is not set in the sessionc
    header("Location: login.php");
    exit();
}

$course_id = $_GET['course_id'];

$stmt = $conn->prepare('SELECT Courses.course_name, Users.first_name, Users.last_name FROM Courses JOIN Users ON Courses.instructor_id = Users.user_id WHERE Courses.course_id = ?');
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $course = $result->fetch_assoc();
} else {
    die('No course found with the given ID.');
}
?>

<h1>Provide Feedback</h1>
<form action="logout.php" method="post">
    <button type="submit" class="btn btn-primary">Logout</button>
</form>
<p>You are providing feedback for the course <?php echo htmlspecialchars($course['course_name']); ?> taught by <?php echo htmlspecialchars($course['first_name'] . ' ' . $course['last_name']); ?></p>
<form action="submit_feedback.php" method="post">
    <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
    <label for="is_anonymous">Anonymous:</label>
    <input type="checkbox" id="is_anonymous" name="is_anonymous"><br>
    <?php foreach ($feedback_questions as $index => $question): ?>
        <label for="rating<?php echo $index + 1; ?>"><?php echo htmlspecialchars($question); ?></label>
        <select name="rating<?php echo $index + 1; ?>" id="rating<?php echo $index + 1; ?>" required>
            <option value="">Select an option</option>
            <option value="1">1 - Very poor</option>
            <option value="2">2 - Poor</option>
            <option value="3">3 - Average</option>
            <option value="4">4 - Good</option>
            <option value="5">5 - Excellent</option>
        </select><br>
    <?php endforeach; ?>

    <label for="feedback_text">Additional Comments:</label>
    <textarea id="feedback_text" name="feedback_text"></textarea><br>
    <button type="submit" class="btn btn-primary">Submit Feedback</button>
</form>

<?php
$action = $_SESSION['role'] === 'student' ? 'student_dashboard.php' : 'instructor_dashboard.php';
?>
<form action="<?php echo $action; ?>" method="post">
    <button type="submit" class="btn btn-primary">Back</button>
</form>



<?php
$content = ob_get_clean();
include 'base.php';
?>
