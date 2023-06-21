<?php
include 'db_connect.php';
$title = 'Instructor Dashboard';
ob_start();

// Start session
session_start();

// Check if user_id is set in the session and retrieve it
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    // Redirect to login page if user_id is not set in the session
    header("Location: login.php");
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
    $stmt = $conn->prepare('SELECT course_id, course_name FROM Courses WHERE instructor_id = ?');
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $courses = [];
    while($row = $result->fetch_assoc()){
        $courses[] = $row;
    }
} catch (Exception $e) {
    die('Cannot retrieve data: ' . $e->getMessage());
}

?>

<h1>Welcome, <?php echo htmlspecialchars($instructor['first_name'] . ' ' . $instructor['last_name']); ?></h1>
<form action="logout.php" method="post">
    <button type="submit" class="btn btn-primary">Logout</button>
</form>

<p>Search for your courses:</p>
<input type="text" id="searchBox" placeholder="Search CRN..." oninput="searchCourses()">
<div id="searchResults"></div>


<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        searchCourses(); // Fetch courses when the page loads
    });

    document.getElementById('searchBox').addEventListener('input', function(e){
        searchCourses();
    });

    function searchCourses() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("searchResults").innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "search_courses.php?CRN=" + document.getElementById("searchBox").value + "&user_id=" + <?php echo $user_id; ?> + "&role=" + "<?php echo $role; ?>", true);
        xhttp.send();
    }
</script>

<?php
$content = ob_get_clean();
include 'base.php';
?>
