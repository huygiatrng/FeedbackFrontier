<?php
include '../../../includes/db_connect.php';

$school_id = $_POST['school_id'];

$stmt = $conn->prepare("SELECT * FROM Users WHERE role = 'instructor' AND school_id = ?");
$stmt->bind_param("i", $school_id);
$stmt->execute();
$result = $stmt->get_result();

$instructors = array();
while($row = $result->fetch_assoc()) {
    $instructors[] = $row;
}
echo json_encode($instructors);
?>
