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

// Fetch schools and instructors
$schools = $conn->query("SELECT * FROM School");
$instructors = $conn->query("SELECT * FROM Users WHERE role = 'instructor'");

// Process the form when it is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_name = htmlspecialchars($_POST['course_name']);
    $CRN = htmlspecialchars($_POST['CRN']);
    $school_id = htmlspecialchars($_POST['school_id']);
    $instructor_id = htmlspecialchars($_POST['instructor_id']);

    // Check if CRN is duplicate in one school
    $duplicate_check = $conn->prepare("SELECT * FROM Courses WHERE CRN = ? AND school_id = ? AND year = ?");
    $duplicate_check->bind_param("sii", $CRN, $school_id, $year);
    $duplicate_check->execute();
    if ($duplicate_check->fetch()) {
        echo "Course with the same CRN already exists in this school.";
    } else {
        // Prepare the UPDATE statement
        $stmt = $conn->prepare("UPDATE Courses SET course_name = ?, CRN = ?, school_id = ?, instructor_id = ? WHERE course_id = ?");
        $stmt->bind_param("ssiii", $course_name, $CRN, $school_id, $instructor_id, $course_id);

        // Execute the statement
        if ($stmt->execute()) {
            header("Location: ../manage_courses.php");
        } else {
            echo "Error: " . $stmt->error;
        }
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
                            <label for="course_name" class="form-label">Course Name:</label>
                            <input type="text" id="course_name" name="course_name" value="<?php echo $course['course_name']; ?>" required class="form-control">
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="CRN" class="form-label">CRN:</label>
                            <input type="text" id="CRN" name="CRN" required value="<?php echo $course['course_name']; ?>" class="form-control">
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
                            <label for="instructor_id" class="form-label">Instructor:</label><br>
                            <select id="instructor_id" name="instructor_id" required class="form-control">
                                <?php while ($instructor = $instructors->fetch_assoc()) : ?>
                                    <option value="<?php echo $instructor['user_id']; ?>" <?php echo ($instructor['user_id'] == $course['instructor_id']) ? 'selected' : ''; ?>><?php echo $instructor['first_name'] . ' ' . $instructor['last_name']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        

                        <div class="col-xl-3 col-md-6 d-flex align-items-end mb-3">
                            <button type="submit" class="btn btn-info">Update Course</button>
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

<script>
    $(document).ready(function() {
        var school_id = $('#school_id').val();
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

        $('#school_id').change(function() {
            var school_id = $(this).val();
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
        });
    });
</script>


<?php
$content = ob_get_clean();
include '../../../includes/base.php';
?>