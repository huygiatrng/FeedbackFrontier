<?php
include '../../includes/db_connect.php';
$title = 'Instructor Dashboard';
$pageHeading = 'Dashboard';
ob_start();

if (!isset($_SESSION)) {
    session_start();
}

// Check if user_id is set in the session and retrieve it
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    // Redirect to login page if user_id is not set in the session
    header("Location: ../authentication/login.php");
    exit();
}

$role = $_SESSION['role'];



try {
    // Get instructor's first_name and last_name from Users where role is 'instructor'
    $stmt = $conn->prepare('SELECT first_name, last_name FROM Users WHERE user_id = ? AND role = ?');
    $role = 'instructor';
    $stmt->bind_param("is", $user_id, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the query returned any rows
    if ($result->num_rows > 0) {
        $instructor = $result->fetch_assoc();
    } else {
        die('No instructor found with the given ID.');
    }

    // Get courses taught by the instructor
    $stmt = $conn->prepare('SELECT c.course_id, c.course_name, u.first_name, u.last_name FROM Courses c JOIN Users u ON c.instructor_id = u.user_id WHERE c.school_id = (SELECT school_id FROM Users WHERE user_id = ? AND role = ?) AND u.role = ?');
    $instructor_role = 'instructor';
    $stmt->bind_param("iss", $user_id, $role, $instructor_role);
    $stmt->execute();
    $result = $stmt->get_result();
    $courses = [];
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
} catch (Exception $e) {
    die('Cannot retrieve data: ' . $e->getMessage());
}

?>
<div class="row">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Welcome, <?php echo htmlspecialchars($instructor['first_name'] . ' ' . $instructor['last_name']); ?></h3>
            </div>
            <div class="card-body pb-0">
                <h6>Search for your courses:</h6>
                <div class="row mb-3">
                    <div class="col-lg-2 col-md-6 mb-3">
                        <select id="semesterBox" onchange="searchCourses()" class="form-control">
                            <option value="">Select a Semester</option>
                            <option value="Spring">Spring</option>
                            <option value="Summer">Summer</option>
                            <option value="Fall">Fall</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6 mb-3">
                        <select id="yearBox" onchange="searchCourses()" class="form-control">
                            <option value="">Select a Year</option>
                            <?php for ($i = 1980; $i <= 2023; $i++) : ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6 mb-3">
                        <input type="text" id="crnBox" class="form-control" placeholder="Search CRN..." oninput="searchCourses()">

                    </div>
                    <div class="col-lg-2 col-md-6 mb-3">
                        <input type="text" id="courseNameBox" class="form-control" placeholder="Search Course Name..." oninput="searchCourses()">

                    </div>
                    <div class="col-lg-2 col-md-6 mb-3">
                        <input type="text" id="instructorBox" class="form-control" placeholder="Search Instructor..." oninput="searchCourses()">
                    </div>

                </div>
            </div>
            <!-- /.card-header -->
            <!-- form start -->

        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title ">Your Courses</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Semester</th>
                            <th>Year</th>
                            <th>CRN</th>
                            <th>Course Name</th>
                            <th>Instructor</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="searchResults">

                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        searchCourses(); // Fetch courses when the page loads
    });

    function searchCourses() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                let data = JSON.parse(this.responseText);
                let html = "";
                Array.from(data).forEach(function(element) {
                    console.log(element);
                    html += `<tr>
                    <td>${element.season}</td>
                    <td>${element.year}</td>
                    <td>${element.CRN}</td>
                    <td>${element.course_name}</td>
                        <td>${element.first_name} ${element.last_name}</td>
                    <td class="text-center"><a class="btn btn-success" href="../course/view_feedback.php?course_id=${element.course_id}">View Feedback</a></td>
                    </tr>`;
                });
                (html == "") ? html = "<tr><td colspan='5'>No course found.</td></tr>": html = html;
                document.getElementById("searchResults").innerHTML = html;

            }
        };
        xhttp.open("GET", "../course/search_courses.php?CRN=" + document.getElementById("crnBox").value + "&course_name=" + document.getElementById("courseNameBox").value + "&instructor=" + document.getElementById("instructorBox").value + "&semester=" + document.getElementById("semesterBox").value + "&year=" + document.getElementById("yearBox").value + "&user_id=" + <?php echo $user_id; ?> + "&role=" + "<?php echo $role; ?>", true);
        xhttp.send();
    }
</script>

<?php
$content = ob_get_clean();
include '../../includes/base.php';
?>