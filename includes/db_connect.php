<?php
$servername = "localhost";
$username = "swegroup1";
$password = "courseproject";
$dbname = "coursefeedbackdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: ". $e->getMessage();
}
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
