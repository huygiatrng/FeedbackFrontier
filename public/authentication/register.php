<?php
include '../../includes/db_connect.php';
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
                        <h2 class="fw-bold mb-5">Sign up now</h2>
                        <form action="submit_registration.php"  method="post">
                            <div class="row">
                                <input type="hidden" id="role" name="role" value="user">
                                <div class="form-group mb-3 col-md-6">
                                    <label for="email">Email:</label>
                                    <input type="email" id="email" name="email" class="form-control" required>
                                </div>
                                <div class="form-group mb-3 col-md-6">
                                    <label for="first_name">First Name:</label>
                                    <input type="text" id="first_name" name="first_name" class="form-control" required>
                                </div>
                                <div class="form-group mb-3 col-md-6">
                                    <label for="last_name">Last Name:</label>
                                    <input type="text" id="last_name" name="last_name" class="form-control" required>
                                </div>
                                <div class="form-group mb-3 col-md-6">
                                    <label for="password">Password:</label>
                                    <input type="password" id="password" name="password" class="form-control" minlength="6" required>
                                </div>
                                <div class="form-group mb-3 col-md-6">
                                    <label for="confirm_password">Confirm Password:</label>
                                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required oninput="checkPassword()">
                                </div>
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
                                        Sign up
                                    </button>

                                </div>

                                <div class="col-12">
                                    <p class="text-center">Already have an account? <a href="login.php">Sign in</a></p>
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

    <script>
        function checkPassword() {
            var password = document.getElementById('password');
            var confirm_password = document.getElementById('confirm_password');
            if (password.value !== confirm_password.value) {
                confirm_password.setCustomValidity("Passwords do not match");
            } else {
                confirm_password.setCustomValidity('');
            }
        }
    </script>
</body>

</html>