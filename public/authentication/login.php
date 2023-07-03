<?php
include '../../includes/db_connect.php';
if (isset($_POST['submit'])) {
    // Get email and password from POST request
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare SQL query
    $stmt = $conn->prepare("SELECT * FROM Users WHERE email = ?");
    $stmt->bind_param("s", $email);

    // Execute the SQL query
    $stmt->execute();
    $result = $stmt->get_result();
    $error = "";
    // If a record is found
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Start session and store user id and role
            session_start();
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];

            // Redirect user to their respective dashboard
            if ($user['role'] == 'admin') {
                header("Location: ../admin/admin_dashboard.php");
            } elseif ($user['role'] == 'user') {
                header("Location: ../user/user_dashboard.php");
            }
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }

    $stmt->close();
    $conn->close();
}
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
                        <h2 class="fw-bold mb-5">Sign in</h2>
                        <form action="" method="post">
                            <div class="row">
                                <div class="form-group mb-4 col-md-12">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control" required>
                                </div>
                                <div class="form-group mb-4 col-md-12">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" id="password" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <p class="text-center text-danger" id="error"><?php if (isset($error)) {
                                            echo $error;
                                        } ?></p>
                                </div>
                                <div class="col-12">
                                    <button type="submit" name="submit" class="btn btn-primary btn-block mb-4">
                                        Log in
                                    </button>
                                </div>
                                <div class="col-12">
                                    <p class="text-center">Don't have an account? <a href="register.php">Sign up</a></p>
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>

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