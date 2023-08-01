<?php
include '../../includes/db_connect.php';

$CRN = $_GET['CRN'] ?? '';
$course_subject = $_GET['course_subject'] ?? '';
$course_number = $_GET['course_number'] ?? '';
$instructor = $_GET['instructor'] ?? '';
$semester = $_GET['semester'] ?? '';
$year = $_GET['year'] ?? '';
$user_id = $_GET['user_id'];
$role = $_GET['role'];

$query_parts = [];

if (!empty($CRN)) {
    $query_parts[] = "Courses.CRN LIKE CONCAT(?,'%')";
}

if (!empty($course_subject)) {
    $query_parts[] = "Courses.course_subject LIKE CONCAT(?,'%')";
}

if (!empty($course_number)) {
    $query_parts[] = "Courses.course_number LIKE CONCAT(?,'%')";
}

if (!empty($instructor)) {
    $query_parts[] = "Courses.instructor_name = ?";
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

$query_string .= " AND Courses.school_id = (SELECT school_id FROM Users WHERE user_id = ?)";
$stmt = $conn->prepare("SELECT Courses.*, Users.first_name, Users.last_name FROM Courses JOIN Users ON Users.user_id = ? WHERE $query_string ORDER BY Courses.CRN LIMIT 20");

$params = array_filter([$CRN, $course_subject, $course_number, $instructor, $semester, $year, $user_id], function ($value) {
    return !empty($value);
});

$stmt_types = str_repeat("s", count($params) + 1); // All params are strings, including user_id

$stmt->bind_param($stmt_types, ...array_merge([$user_id], $params));

$stmt->execute();
$result = $stmt->get_result();
echo json_encode(mysqli_fetch_all($result, MYSQLI_ASSOC));
?>
