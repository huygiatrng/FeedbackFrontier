<?php
include '../../includes/db_connect.php';
include '../../includes/feedback_questions.php';
include '../../src/Course.php';

$title = 'Course Feedbacks';
$pageHeading = 'Course Feedbacks';
ob_start();

// Start session
session_start();

// Check if user_id is set in the session and retrieve it
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user_id is not set in the session
    header("Location: ../authentication/login.php");
    exit();
}
$role = $_SESSION['role'];
$course_id = $_GET['course_id'];


$action = $_SESSION['role'] === 'user' ? '../user/user_dashboard.php' : '../admin/admin_dashboard.php';

$stmt = $conn->prepare('SELECT Courses.course_id,Courses.course_subject, Courses.course_number,Courses.CRN, Courses.season, Courses.year, Courses.instructor_name FROM Courses WHERE course_id=?');
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $course = $result->fetch_assoc();
    $course_id = htmlspecialchars($course['course_id']);
    $courseSub = htmlspecialchars($course['course_subject']);
    $courseNum = htmlspecialchars($course['course_number']);
    $courseInstance = new Course($conn, $course_id); // Create an instance of Course class
    $courseTitle = $courseInstance->getCourseTitle($courseNum, $courseSub); // Get course title
    $crn = htmlspecialchars($course['CRN']);
    $season = htmlspecialchars($course['season']);
    $year = htmlspecialchars($course['year']);
    $instructorName = htmlspecialchars($course['instructor_name']);
} else {
    die('No course found with the given ID.');
}

$stmt = $conn->prepare('SELECT Users.first_name, Users.last_name, Feedback.feedback_id, Feedback.rating1, Feedback.rating2, Feedback.rating3, Feedback.rating4, Feedback.rating5, Feedback.rating6, Feedback.rating7, Feedback.feedback_text, Feedback.anonymous, Feedback.createdAt FROM Feedback INNER JOIN Users ON Users.user_id = Feedback.user_id WHERE Feedback.course_id = ?');
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();
$feedbacks = [];
while ($row = $result->fetch_assoc()) {
    $feedbacks[] = $row;
}

?>

    <div id="header-page" class="col-12 pl-0 mt-4 mb-4 flex-wrap"
         style="display: flex; justify-content: column; justify-content: center; align-items: center;">
        <div class="col-md-6 pl-5">
            <div class="card card-primary border-primary mb-4">
                <div class="card-header"><h1><?php echo "$courseTitle"; ?></h1></div>
                <div class="card-header bg-orange text-white pb-0">
                    <h2 class="card-title">
                        <strong><?php echo "$season $year - $courseSub $courseNum"; ?></strong>
                    </h2>
                </div>
                <div class="col-12 row flex-wrap ">
                    <div class="col-md-4">
                        <div class="card-body bg-white" style="width: 20em;">
                            <p class="card-text" style="font-size: 2.5em;">
                            <div>
                                <strong>Instructor: </strong><?php echo "$instructorName"; ?></p>
                                <strong>CRN: </strong><?php echo "$crn"; ?></p>
                                <?php echo "<strong>Number of feedback: </strong>" . count($feedbacks); ?></p>
                                <?php
                                $avgRating = Course::calculateAverageRatingOfCourse($course);
                                echo "<strong>Average Rating: </strong>" . $avgRating;
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 pt-5 pb-4 ">
                        <div class="d-flex flex-wrap justify-content-center ">
                            <?php
                            echo "<a class='btn btn-success m-2' style='font-size: 2.0em;width: 10em; color: white;' href='../course/course_feedback.php?course_id=$course_id'>Provide Feedback</a>";
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 d-flex justify-content-center">
            <div class="h-5 w-5">
                <div class="card border-primary chart-container" style="overflow:hidden" id="chart_div"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header pb-0">
                    <h3 class="card-title"><h2>Feedbacks</h2></h3>
                </div>
                <!-- ./card-header -->
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th style="width:3%;">#</th>
                            <th style="width:20%;">From</th>
                            <th style="width:10%;">Average Rating</th>
                            <th style="width:35%;">Comments</th>
                            <th style="width:20%;">Submitted On</th>
                            <th style="width:12%;">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $count = 1;
                        foreach ($feedbacks as $feedback) : ?>
                            <tr data-widget="expandable-table" aria-expanded="false">
                                <td><?php echo $count; ?></td>
                                <td><?php echo $feedback['anonymous'] ? 'Anonymous' : htmlspecialchars($feedback['first_name'] . ' ' . $feedback['last_name']); ?></td>
                                <td style="text-align: center;"><strong
                                            style="color: orangered;font-size:1.5rem;"><?php echo round(Feedback::calculateAverageRating($feedback), 1); ?></strong>
                                </td>
                                <td><?php echo htmlspecialchars($feedback['feedback_text']); ?></td>
                                <td><?php echo date("F j, Y, g:i a", strtotime($feedback['createdAt'])); ?></td>
                                <td>
                                    <button class='btn btn-danger report-btn'
                                            data-feedback-id='<?php echo $feedback["feedback_id"]; ?>'
                                            data-course-id='<?php echo $course_id; ?>'>Report
                                    </button>
                                </td>
                            </tr>
                            <tr class="expandable-body">
                                <td colspan="<?php echo 5; ?>" class="">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Question</th>
                                            <th>Rating</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($feedback_questions as $index => $question) {
                                            $ratingIndex = 'rating' . ($index + 1);
                                            echo '<tr><td>' . htmlspecialchars($question) . "</td><td>" . $feedback[$ratingIndex] . '</td></tr>';
                                        } ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <?php $count++;
                        endforeach;
                        if ($count == 1) {
                            echo "<tr><td colspan='6' class='text-center'>No feedback found.</td></tr>";
                        } ?>

                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>

    <div class="modal" id="reportModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Report Feedback</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <form id="reportForm" method="POST" action="handle_report.php">
                        <div class="form-group">
                            <label for="reason_option">Reason:</label>
                            <select class="form-control" id="reason_option" name="reason_option">
                                <option>This feedback includes another name exclude instructor</option>
                                <option>This feedback has inappropriate information</option>
                                <option>This feedback is Spam or Misleading</option>
                                <option>Feedback is Violent or repulsive content</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="reason_text">Details:</label>
                            <textarea class="form-control" id="reason_text" name="reason_text" rows="3"></textarea>
                        </div>
                        <input type="hidden" id="feedback_id" name="feedback_id">
                        <input type="hidden" id="course_id" name="course_id">
                    </form>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="submitReport">Submit</button>
                </div>

            </div>
        </div>
    </div>

    <a class="btn btn-secondary" href="../user/user_dashboard.php">Back to Dashboard</a>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>


    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
        google.load("visualization", "1", {packages: ["corechart"]});
        google.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Average Rating');
            data.addColumn('number', 'Count');
            data.addColumn({type: 'string', role: 'annotation'}); // add annotation column role
            var ratings = [];

            <?php foreach ($feedbacks as $feedback) {
            $averageRating = floor(Feedback::calculateAverageRating($feedback) + 0.5);
            echo "ratings.push($averageRating);";
        } ?>

            var ratingCounts = {}, i, value, size;
            size = ratings.length;

            for (i = 0; i < size; i++) {
                value = ratings[i];
                if (typeof ratingCounts[value] === "undefined") {
                    ratingCounts[value] = 1;
                } else {
                    ratingCounts[value]++;
                }
            }

            // Add all possible ratings (1 to 5) to ratingCounts if they don't exist
            for (var rating = 1; rating <= 5; rating++) {
                if (typeof ratingCounts[rating] === "undefined") {
                    ratingCounts[rating] = 0;
                }
            }

            for (rating in ratingCounts) {
                if (ratingCounts.hasOwnProperty(rating)) {
                    // add count value as annotation
                    data.addRow([rating, ratingCounts[rating], ratingCounts[rating].toString()]);
                }
            }

            var options = {
                title: 'Distribution of Average Ratings',
                width: 400,
                height: 300,
                legend: 'none',
                hAxis: {
                    title: null,
                    gridlines: {
                        color: 'transparent'
                    },
                    ticks: [] // hide x-axis ticks
                },
                vAxis: {
                    title: null,
                    gridlines: {
                        color: 'transparent'
                    },
                    ticks: [1, 2, 3, 4, 5]
                },
                chartArea: {
                    width: '80%',
                    height: '80%'
                },
                titleTextStyle: {
                    fontSize: 20 // increase title text size
                }
            };

            var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>


    <script type="text/javascript">
        $(document).ready(function () {
            // Handle row click
            $('tr[data-widget="expandable-table"]').click(function () {
                var $expandableBody = $(this).next('.expandable-body');
                if ($expandableBody.is(':visible')) {
                    $expandableBody.hide('fast');
                } else {
                    $expandableBody.show('fast');
                }
            });

            // Handle report button click
            $(".report-btn").click(function (event) {
                event.stopPropagation();
                var feedback_id = $(this).data('feedback-id');
                $("#feedback_id").val(feedback_id);
                var course_id = $(this).data('course-id');
                $("#course_id").val(course_id);
                $('#reportModal').modal('show');
            });

            $("#submitReport").click(function (e) {
                e.preventDefault(); // prevent form from submitting normally
                var feedback_id = $("#feedback_id").val();
                var course_id = $("#course_id").val();
                var reason_option = $("#reason_option").val();
                var reason_text = $("#reason_text").val();

                $.ajax({
                    type: "POST",
                    url: "handle_report.php",
                    data: {
                        feedback_id: feedback_id,
                        course_id: course_id,
                        reason_option: reason_option,
                        reason_text: reason_text
                    },
                    success: function (response) {
                        // close the report modal
                        $('#reportModal').modal('hide');

                        // create a new Bootstrap modal
                        var modal = $(
                            '<div class="modal fade show d-block" id="successModal" tabindex="-1" role="dialog">' +
                            '<div class="modal-dialog modal-dialog-centered">' +
                            '<div class="modal-content">' +
                            '<div class="modal-body text-center">' +
                            '<p class="h3 mb-3">Successfully reported!</p>' +
                            "</div>" +
                            "</div>" +
                            "</div>" +
                            "</div>"
                        );

                        // append the modal to the body
                        $('body').append(modal);

                        // fade out the modal after 1 second
                        setTimeout(function () {
                            $('#successModal').fadeOut(500, function () {
                                $(this).remove();
                            });
                        }, 1000);
                    },
                    error: function (xhr, status, error) {
                        // handle error
                        console.log(status, error);
                        alert("An error occurred. Please try again later.");
                    }
                });
            });
        });
    </script>

    <style>
        @header-page (max-width: 768px) {
            .flex {
                flex-direction: column;
            }
        }
    </style>

<?php
$content = ob_get_clean();
include '../../includes/base.php';
?>