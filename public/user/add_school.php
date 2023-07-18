<?php
ob_start(); // start buffering output

include '../../includes/db_connect.php';
include '../../src/User.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    // If the user_id is not set in the session, redirect to login
    header('Location: login.php');
    exit();
}

// Fetch current user data
$user_id = $_SESSION['user_id'];
$query = $conn->prepare("SELECT * FROM Users WHERE user_id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

// If the user's school_id is not null, redirect to dashboard
if (!is_null($user['school_id'])) {
    header('Location: user_dashboard.php');
    exit();
}

try {
    // Fetch all schools from the database
    $query = $conn->query('SELECT * FROM School');
    $schools = array();
    while ($row = $query->fetch_assoc()) {
        $schools[] = $row;
    }
} catch (Exception $e) {
    die('Database connection failed: ' . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Change the school id for a user
    $new_school_id = $_POST['school_id'];

    $query = $conn->prepare("UPDATE Users SET school_id = ? WHERE user_id = ?");
    $query->bind_param("ii", $new_school_id, $user_id);

    if ($query->execute()) {
        // If it was successful, redirect to the user dashboard
        header('Location: user_dashboard.php');
        exit;
    } else {
        // If it was not successful, set an error message
        $message = "Error changing school.";
    }
}


ob_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- CSS only -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../assets/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="../../assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../assets/css/index.css">
</head>

<body>
<!-- Section: Design Block -->
<section class="text-center">
    <!-- Background image -->
    <div class="p-5 bg-image" style="
        background-image: url('../../assets/img/students.jpg');
        height: 300px;
        "></div>
    <!-- Background image -->

    <div class="card mx-4 mx-md-5 shadow-5-strong" style="
        margin-top: -100px;
        background: hsla(0, 0%, 100%, 0.8);
        backdrop-filter: blur(30px);
        ">
        <div class="card-body py-5 px-md-5">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-8">
                    <h2 class="fw-bold mb-5">Change School</h2>
                    <?php if (isset($message)) echo "<p class=\"text-center\">$message</p>" ?>
                    <form action="add_school.php" method="POST">
                        <div class="row">
                            <div class="form-group mb-3 col-md-12">
                                <label for="school_id">School:</label>
                                <select name="school_id" id="school_id" class="form-control" required>
                                    <option value="">Select a School</option>
                                    <?php foreach ($schools as $school) : ?>
                                        <option value="<?php echo $school['school_id']; ?>"><?php echo $school['school_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-block mb-4">
                                    Change School
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Section: Design Block -->
<!-- jQuery -->
<script src="../../assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/index.js"></script>

</body>

</html>
