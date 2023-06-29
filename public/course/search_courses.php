<?php
include '../../includes/db_connect.php';

$CRN = $_GET['CRN'] ?? '';
$course_name = $_GET['course_name'] ?? '';
$instructor = $_GET['instructor'] ?? '';
$semester = $_GET['semester'] ?? '';
$year = $_GET['year'] ?? '';
$user_id = $_GET['user_id'];
$role = $_GET['role'];

$query_parts = [];

if (!empty($CRN)) {
    $query_parts[] = "Courses.CRN LIKE CONCAT(?,'%')";
}

if (!empty($course_name)) {
    $query_parts[] = "Courses.course_name LIKE CONCAT(?,'%')";
}

if (!empty($instructor)) {
    $query_parts[] = "(Users.first_name LIKE CONCAT(?,'%') OR Users.last_name LIKE CONCAT(?,'%'))";
}

if (!empty($semester)) {
    $query_parts[] = "Courses.season = ?";
}

if (!empty($year)) {
    $query_parts[] = "Courses.year = ?";
}

$query_string = "1 = 1";

if (!empty($query_parts)) {
    $query_string .= " AND " . implode(" AND ", $query_parts);
}

if ($role == 'instructor') {
    $query_string .= " AND Courses.instructor_id = ?";
    $stmt = $conn->prepare("SELECT Courses.*, Users.first_name, Users.last_name FROM Courses JOIN Users ON Courses.instructor_id = Users.user_id WHERE $query_string ORDER BY Courses.CRN LIMIT 20");
} else {
    $query_string .= " AND Courses.school_id = (SELECT school_id FROM Users WHERE user_id = ?)";
    $stmt = $conn->prepare("SELECT Courses.*, Users.first_name, Users.last_name FROM Courses JOIN Users ON Courses.instructor_id = Users.user_id WHERE $query_string ORDER BY Courses.CRN LIMIT 20");
}

$params = array_filter([$CRN, $course_name, $instructor, $instructor, $semester, $year, $user_id], function ($value) {
    return !empty($value);
});

$stmt_types = str_repeat("s", count($params) - 1) . "i"; // All params are strings except the last one (user_id), which is an integer

$stmt->bind_param($stmt_types, ...$params);

$stmt->execute();
$result = $stmt->get_result();
echo json_encode(mysqli_fetch_all($result, MYSQLI_ASSOC));
// while ($row = $result->fetch_assoc()) {
    // echo json_encode($row);
    // echo "<p>";
    // echo htmlspecialchars($row['season']) . " " . htmlspecialchars($row['year']) . " - CRN: " . htmlspecialchars($row['CRN']) . " - " . htmlspecialchars($row['course_name']) . " - " . htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']);
    // if($role == 'student'){
    //     echo ' <a href="../course/course_feedback.php?course_id='. htmlspecialchars($row['course_id']).'">Provide Feedback</a>';
    // }
    // echo ' <a href="../course/view_feedback.php?course_id='. htmlspecialchars($row['course_id']).'">View Feedback</a>';
    // echo "</p>";
// }
