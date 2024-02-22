<?php
include '../../includes/db_connect.php';
require_once '../../src/Course.php';

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
    $query_parts[] = "c.CRN LIKE CONCAT(?,'%')";
}

if (!empty($course_subject)) {
    $query_parts[] = "c.course_subject LIKE CONCAT(?,'%')";
}

if (!empty($course_number)) {
    $query_parts[] = "c.course_number LIKE CONCAT(?,'%')";
}

if (!empty($instructor)) {
    $query_parts[] = "c.instructor_name LIKE CONCAT(?,'%')";
}

// Ensure both semester and year are provided to include them in the search
if (!empty($semester) && !empty($year)) {
    $query_parts[] = "c.season = ?";
    $query_parts[] = "c.year = ?";
} else {
    // Reset semester and year to empty if both are not provided
    $semester = '';
    $year = '';
}

$query_string = "1 = 1";

if (!empty($query_parts)) {
    $query_string .= " AND " . implode(" AND ", $query_parts);
}

$query_string .= " AND c.school_id = (SELECT school_id FROM Users WHERE user_id = ?)";

$stmt = $conn->prepare("
    SELECT c.*, u.first_name, u.last_name,
           (SELECT COUNT(f.feedback_id) FROM feedback f WHERE f.course_id = c.course_id) AS feedback_count
    FROM Courses c
    JOIN Users u ON u.user_id = ?
    WHERE $query_string
    ORDER BY c.CRN
    LIMIT 20");

$params = array_filter([$CRN, $course_subject, $course_number, $instructor, $semester, $year, $user_id], function ($value) {
    return !empty($value);
});

$stmt_types = str_repeat("s", count($params) + 1); // All params are strings, including user_id

$stmt->bind_param($stmt_types, ...array_merge([$user_id], $params));

$stmt->execute();
$result = $stmt->get_result();
echo json_encode(mysqli_fetch_all($result, MYSQLI_ASSOC));
?>
