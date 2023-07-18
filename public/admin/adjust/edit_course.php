<?php
ob_start();
include '../../../includes/db_connect.php';
$title = 'Edit Course';
$pageHeading = 'Edit Course';

// Start session
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Only allow access to admins
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../../authentication/login.php");
    exit();
}

// Get the course_id from the URL
$course_id = $_GET['course_id'];

// Fetch the course data
$stmt = $conn->prepare("SELECT * FROM Courses WHERE course_id = ?");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();

// Fetch schools
$schools = $conn->query("SELECT * FROM School");

$seasons = ['Spring', 'Summer', 'Fall', 'Winter'];
$years = range(1980, 2023);

// Process the form when it is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_subject = htmlspecialchars($_POST['course_subject']);
    $course_number = htmlspecialchars($_POST['course_number']);
    $CRN = htmlspecialchars($_POST['CRN']);
    $school_id = htmlspecialchars($_POST['school_id']);
    $instructor_name = htmlspecialchars($_POST['instructor_name']);

    $season = htmlspecialchars($_POST['season']);
    $year = htmlspecialchars($_POST['year']);

    // Prepare the UPDATE statement
    $stmt = $conn->prepare("UPDATE Courses SET course_subject = ?, course_number = ?, CRN = ?, school_id = ?, instructor_name = ?, season = ?, year = ? WHERE course_id = ?");
    $stmt->bind_param("ssiissii", $course_subject, $course_number, $CRN, $school_id, $instructor_name, $season, $year, $course_id);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: ../manage_courses.php");
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<div class="row mb-3">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Edit Course</h3>
            </div>
            <div class="card-body">
                <form action="" method="post">
                    <div class="row mb-3">
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="course_subject" class="form-label">Course Subject:</label>
                            <input type="text" id="course_subject" name="course_subject" value="<?php echo $course['course_subject']; ?>" required class="form-control">
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="course_number" class="form-label">Course Number:</label>
                            <input type="text" id="course_number" name="course_number" value="<?php echo $course['course_number']; ?>" required class="form-control">
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="season" class="form-label">Season:</label>
                            <select id="season" name="season" required class="form-control">
                                <?php foreach ($seasons as $season): ?>
                                    <option value="<?php echo $season; ?>" <?php echo ($season == $course['season']) ? 'selected' : ''; ?>><?php echo $season; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="year" class="form-label">Year:</label>
                            <select id="year" name="year" required class="form-control">
                                <?php foreach ($years as $year): ?>
                                    <option value="<?php echo $year; ?>" <?php echo ($year == $course['year']) ? 'selected' : ''; ?>><?php echo $year; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="CRN" class="form-label">CRN:</label>
                            <input type="text" id="CRN" name="CRN" required value="<?php echo $course['CRN']; ?>" class="form-control">
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="school_id" class="form-label">School:</label>
                            <select id="school_id" name="school_id" required class="form-control">
                                <?php while ($school = $schools->fetch_assoc()) : ?>
                                    <option value="<?php echo $school['school_id']; ?>" <?php echo ($school['school_id'] == $course['school_id']) ? 'selected' : ''; ?>><?php echo $school['school_name']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="instructor_name" class="form-label">Instructor Name:</label>
                            <input type="text" id="instructor_name" name="instructor_name" required value="<?php echo $course['instructor_name']; ?>" class="form-control">
                        </div>
                        <div class="col-xl-3 col-md-6 d-flex align-items-end mb-3">
                            <button type="submit" class="btn btn-info">Update Course</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-header -->
        </div>
    </div>
</div>

<a class="btn btn-secondary" href="../manage_courses.php">Back to Courses</a>

<?php
$content = ob_get_clean();
include '../../../includes/base.php';
?>
