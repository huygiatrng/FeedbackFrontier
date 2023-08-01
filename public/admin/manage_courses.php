<?php
include '../../includes/db_connect.php';
include '../../src/Course.php'; // Include the Course class file

$title = 'Manage Courses';
$pageHeading = 'Manage Courses';
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


// Check if year is set
if (isset($_GET['year']) && isset($_GET['season']) && isset($_GET['subject'])) {
    $year = $_GET['year']; // Make sure to validate and sanitize this input
    $season = $_GET['season']; // Make sure to validate and sanitize this input
    $subject = $_GET['subject']; // Make sure to validate and sanitize this input

    $query = "SELECT course_id, crn, course_name, instructor_name, feedback_count, created_at 
              FROM Courses 
              WHERE year = ? AND season = ? AND course_subject = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $year, $season, $subject); // year is integer, season and subject are strings
    $stmt->execute();
    $result = $stmt->get_result();

    $courses = [];
    while ($row = $result->fetch_assoc()) {
        $courses[] = [
            'id' => $row['course_id'],
            'crn' => $row['crn'],
            'courseName' => $row['course_name'],
            'instructorName' => $row['instructor_name'],
            'feedbackCount' => $row['feedback_count'],
            'createdTime' => date("F j, Y, g:i a", strtotime($row['created_at'])),
        ];
    }

    echo json_encode($courses);
    exit();
}

$query = "SELECT DISTINCT course_subject FROM course_titles ORDER BY course_subject";
$result = $conn->query($query);
$subjects = [];
while ($row = $result->fetch_assoc()) {
    $subjects[] = $row['course_subject'];
}

?>
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title ">All Courses </h3>

                    <select id="seasonSelector" class="custom-select custom-select ml-5 col-2" aria-label=".form-select-lg example">
                        <option selected="">Season</option>
                        <option value="spring">Spring</option>
                        <option value="summer">Summer</option>
                        <option value="fall">Fall</option>
                        <option value="winter">Winter</option>
                    </select>

                    <select id="yearSelector" class="custom-select custom-select col-1" aria-label=".form-select-lg example">
                        <?php for ($i = 2023; $i >= 1980; $i--) : ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                    <select id="subjectSelector" class="custom-select custom-select col-1" aria-label=".form-select-lg example">
                        <option selected="">Subject</option>
                        <?php foreach ($subjects as $subject): ?>
                            <option value="<?php echo htmlspecialchars($subject); ?>"><?php echo htmlspecialchars($subject); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <a href="adjust/add_course.php" class="float-right btn-sm btn-light text-dark">
                        <i class="fa fa-plus-square" aria-hidden="true"></i> Add New Course
                    </a>
                </div>

                <!-- /.card-header -->
                <div class="card-body table-responsive">
                    <table class="datatable table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Season</th>
                            <th>Year</th>
                            <th>CRN</th>
                            <th>Course Name</th>
                            <th>Instructor Name</th>
                            <th>Feedback Count</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Initially empty body, rows will be inserted with JavaScript -->
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>

    <a class="btn btn-secondary" href="../admin/admin_dashboard.php">Back to Dashboard</a>


<?php
$content = ob_get_clean();
ob_start();
?>

    <script src="../../assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../../assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../../assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../../assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script>
        var table = $('.datatable').DataTable();

        $('#yearSelector, #seasonSelector, #subjectSelector').change(function() {
            var year = $('#yearSelector').val();
            var season = $('#seasonSelector').val();
            var subject = $('#subjectSelector').val();

            // Only fetch data when year, season and subject are selected
            if(year && season && subject) {
                $.ajax({
                    url: "fetch_courses.php",
                    type: "GET",
                    data: {year: year, season: season, subject: subject},
                    dataType: "json",
                    success: function(data) {
                        table.clear();

                        if (data.length > 0) {
                            $.each(data, function(i, item) {
                                var capitalizedYear = year.charAt(0).toUpperCase() + year.slice(1);
                                table.row.add([
                                    item.id,
                                    season,
                                    capitalizedYear,
                                    item.crn,
                                    item.courseName,
                                    item.instructorName,
                                    '<a href="view/view_feedback_of_course.php?course_id=' + item.id + '">' + item.feedbackCount + '</a>',
                                    item.createdTime,
                                    '<a href="adjust/edit_course.php?course_id=' + item.id + '" class="btn btn-sm btn-warning text-white"> <i class="fas fa-edit"></i></a> |' +
                                    '<a href="adjust/delete_course.php?course_id=' + item.id + '" onclick="deleteAlert(event);" class="btn btn-sm btn-danger"> <i class="fa fa-trash" aria-hidden="true"></i></a>'
                                ]).draw();
                            });
                        } else {
                            // Empty the table
                            table.draw();
                        }
                    }
                });
            }
        });
    </script>



<?php
$pageJs = ob_get_clean();
include '../../includes/base.php';
?>