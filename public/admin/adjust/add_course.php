<?php
include '../../../includes/db_connect.php';
include '../../../src/Course.php';
$title = 'Add Course';

// Start session
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Only allow access to admins
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../../authentication/login.php");
    exit();
}

$schools = $conn->query("SELECT * FROM School");

// Process the form when it is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_subject = htmlspecialchars($_POST['course_subject']);
    $course_number = htmlspecialchars($_POST['course_number']);
    $CRN = htmlspecialchars($_POST['CRN']);
    $school_id = htmlspecialchars($_POST['school_id']);
    $instructor_name = htmlspecialchars($_POST['instructor_name']);
    $season = htmlspecialchars($_POST['season']);
    $year = htmlspecialchars($_POST['year']);

    // Check if CRN is duplicate in one school in a specific season and year
    $duplicate_check = $conn->prepare("SELECT * FROM Courses WHERE CRN = ? AND school_id = ? AND year = ?");
    $duplicate_check->bind_param("sii", $CRN, $school_id, $year);
    $duplicate_check->execute();
    if ($duplicate_check->fetch()) {
        $error = "Course with the same CRN already exists in this school for the selected season and year.";
    } else {
        // Add the new course
        try {
            $new_course = Course::addCourse($course_subject, $course_number, $CRN, $school_id, $instructor_name, $season, $year);
            header("Location: ../manage_courses.php");
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

ob_start();
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<div class="row mb-3">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Add Course</h3>
            </div>
            <div class="card-body">
                <form action="" method="post">
                    <div class="row mb-3">
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="course_subject" class="form-label">Course Subject:</label>
                            <input type="text" id="course_subject" name="course_subject" required class="form-control">
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="course_number" class="form-label">Course Number:</label>
                            <input type="text" id="course_number" name="course_number" required  class="form-control">
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="CRN" class="form-label">CRN:</label>
                            <input type="text" id="CRN" name="CRN" required  class="form-control">
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="school_id" class="form-label">School:</label>
                            <select id="school_id" name="school_id" required class="form-control">
                                <?php while ($school = $schools->fetch_assoc()) : ?>
                                    <option value="<?php echo $school['school_id']; ?>"><?php echo $school['school_name']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="instructor_name" class="form-label">Instructor Name:</label>
                            <input type="text" id="instructor_name" name="instructor_name" required class="form-control">
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="season" class="form-label">Season:</label>
                            <select id="season" name="season" required class="form-control">
                                <option value="Spring">Spring</option>
                                <option value="Summer">Summer</option>
                                <option value="Fall">Fall</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="course_name" class="form-label">Year:</label>
                            <select id="year" name="year" required class="form-control">
                                <?php for ($y = date("Y"); $y >= 1940; $y--) : ?>
                                    <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="col-xl-3 col-md-6 d-flex align-items-end mb-3">
                            <button type="submit" class="btn btn-info">Save Course</button>
                        </div>
                        <div class="col-12">
                            <?php
                            if (isset($error)) {
                                echo '<p class="text-danger">' . $error . '.</p>';
                            }
                            ?>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-header -->
        </div>
    </div>
</div>

<a class="btn btn-secondary" href="../manage_courses.php">Back to Dashboard</a>

<?php
$content = ob_get_clean();
include '../../../includes/base.php';
?>
