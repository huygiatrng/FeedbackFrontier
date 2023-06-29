<?php
include '../../../includes/db_connect.php';
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
$instructors = $conn->query("SELECT * FROM Users WHERE role = 'instructor'");

// Process the form when it is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_name = htmlspecialchars($_POST['course_name']);
    $CRN = htmlspecialchars($_POST['CRN']);
    $school_id = htmlspecialchars($_POST['school_id']);
    $instructor_id = htmlspecialchars($_POST['instructor_id']);
    $season = htmlspecialchars($_POST['season']);
    $year = htmlspecialchars($_POST['year']);

    // Check if CRN is duplicate in one school in a specific season and year
    $duplicate_check = $conn->prepare("SELECT * FROM Courses WHERE CRN = ? AND school_id = ? AND year = ?");
    $duplicate_check->bind_param("sii", $CRN, $school_id, $year);
    $duplicate_check->execute();
    if ($duplicate_check->fetch()) {
        $error = "Course with the same CRN already exists in this school for the selected season and year.";
    } else {
        // Prepare the INSERT statement
        $stmt = $conn->prepare("INSERT INTO Courses (course_name, CRN, school_id, instructor_id, season, year) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiiis", $course_name, $CRN, $school_id, $instructor_id, $season, $year);

        // Execute the statement
        if ($stmt->execute()) {
            header("Location: ../manage_courses.php");
        } else {
            $error = "Error: " . $stmt->error;
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
                            <label for="course_name" class="form-label">Course Name:</label>
                            <input type="text" id="course_name" name="course_name" required class="form-control">
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
                            <label for="instructor_id" class="form-label">Instructor:</label><br>
                            <select id="instructor_id" name="instructor_id" required class="form-control">
                                <?php while ($instructor = $instructors->fetch_assoc()) : ?>
                                    <option value="<?php echo $instructor['user_id']; ?>"><?php echo $instructor['first_name'] . ' ' . $instructor['last_name']; ?></option>
                                <?php endwhile; ?>
                            </select>
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


<script>
    $(document).ready(function() {
        // Function to fetch instructors
        function fetchInstructors(school_id) {
            $.ajax({
                url: 'fetch_instructors.php',
                type: 'post',
                data: {
                    school_id: school_id
                },
                dataType: 'json',
                success: function(response) {
                    var len = response.length;
                    $("#instructor_id").empty();
                    for (var i = 0; i < len; i++) {
                        var id = response[i]['user_id'];
                        var name = response[i]['first_name'] + ' ' + response[i]['last_name'];
                        $("#instructor_id").append("<option value='" + id + "'>" + name + "</option>");
                    }
                }
            });
        }

        // Fetch instructors when the page loads
        fetchInstructors($("#school_id").val());

        // Fetch instructors when the selected school changes
        $('#school_id').change(function() {
            fetchInstructors($(this).val());
        });
    });
</script>

<?php
$content = ob_get_clean();
include '../../../includes/base.php';
?>