<?php
include 'db_connect.php';

$CRN = $_GET['CRN'];
$user_id = $_GET['user_id'];
$role = $_GET['role'];

$stmt = null;

if($role == 'instructor'){
    $stmt = $conn->prepare("SELECT * FROM Courses JOIN Users ON Courses.instructor_id = Users.user_id WHERE Courses.CRN LIKE CONCAT(?,'%') AND Courses.instructor_id = ? ORDER BY Courses.CRN LIMIT 10");
} else {
    $stmt = $conn->prepare("SELECT * FROM Courses JOIN Users ON Courses.instructor_id = Users.user_id WHERE Courses.CRN LIKE CONCAT(?,'%') AND Courses.school_id = (SELECT school_id FROM Users WHERE user_id = ?) ORDER BY Courses.CRN LIMIT 10");
}
$stmt->bind_param("si", $CRN, $user_id);
$stmt->execute();
$result = $stmt->get_result();

while($row = $result->fetch_assoc()){
    echo "<p>";
    echo "CRN: " . htmlspecialchars($row['CRN']) . " - ";
    echo "Course: " . htmlspecialchars($row['course_name']) . " - ";
    echo "Instructor: " . htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']);
    if($role == 'student'){
        echo ' <a href="course_feedback.php?course_id='. htmlspecialchars($row['course_id']).'">Provide Feedback</a>';
    }
    echo ' <a href="view_feedback.php?course_id='. htmlspecialchars($row['course_id']).'">View Feedback</a>';
    echo "</p>";
}
?>
