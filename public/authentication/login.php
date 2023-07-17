<?php
include '../../includes/db_connect.php';
require_once '../../src/User.php';
require 'config.php';

// Start session
if (!isset($_SESSION)) {
    session_start();
}

if(isset($_SESSION['user_id'])){
    header('Location: ../user/user_dashboard.php');
    exit;
}

require 'google-api/vendor/autoload.php';
$client = new Google_Client();
$client->setClientId('936831618789-hjqg7dnc46m4bq8tihek07nn9nt6ttfk.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/course/public/authentication/GoogleLogin.php');
$client->addScope("email");
$client->addScope("profile");

$google_login_url = $client->createAuthUrl();

if (isset($_POST['submit'])) {
    // Get email and password from POST request
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Using User::authenticate to check for valid user
    $user = User::authenticate($email, $password, $conn);

    if ($user) {
        // Start session and store user id and role
        $_SESSION['user_id'] = $user->user_id;
        $_SESSION['role'] = $user->role;

        // Redirect user to their respective dashboard
        if ($user->role == 'admin') {
            header("Location: ../admin/admin_dashboard.php");
        } elseif ($user->role == 'user') {
            header("Location: ../user/user_dashboard.php");
        }
    } else {
        $error = "Invalid email or password.";
    }
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
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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
                            <div class="col-8">
                                <p class="text-center text-danger" id="error"><?php if (isset($error)) {
                                        echo $error;
                                    } ?></p>
                            </div>

                            <div class="col-12">
                                <button type="submit" name="submit" class="btn btn-primary btn-block mb-4">
                                    Log in
                                </button>
                            </div>

                            <div class="or-container col-12"><div class="line-separator"></div> <div class="or-label">or</div><div class="line-separator"></div></div>


                            <div class="col-12 mt-2 mb-3">
                                <a onclick="window.location.href='<?php echo $google_login_url; ?>'"
                                   class="btn btn-lg btn-google btn-block text-uppercase btn-outline" href="#"><img
                                            src="https://img.icons8.com/color/16/000000/google-logo.png"> Signup Using
                                    Google</a>
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

<style>
    body {
        background-color: #f2f7fb
    }

    .login-block .auth-box {
        margin: 20px auto 0;
        max-width: 450px !important
    }

    .card {
        border-radius: 5px;
        -webkit-box-shadow: 0 0 5px 0 rgba(43, 43, 43, .1), 0 11px 6px -7px rgba(43, 43, 43, .1);
        box-shadow: 0 0 5px 0 rgba(43, 43, 43, .1), 0 11px 6px -7px rgba(43, 43, 43, .1);
        border: none;
        margin-bottom: 30px;
        -webkit-transition: all .3s ease-in-out;
        transition: all .3s ease-in-out;
        background-color: #fff;
    }

    .card .card-block {
        padding: 1.25rem
    }


    .form-group {
        margin-bottom: 1.25em
    }

    .form-material .form-control {
        display: inline-block;
        height: 43px;
        width: 100%;
        border: none;
        border-radius: 0;
        font-size: 16px;
        font-weight: 400;
        padding: 9px;
        background-color: transparent;
        -webkit-box-shadow: none;
        box-shadow: none;
        border-bottom: 1px solid #ccc
    }

    .btn-primary {
        background-color: #4099ff;
        border-color: #4099ff;
        color: #fff;
        cursor: pointer;
        -webkit-transition: all ease-in .3s;
        transition: all ease-in .3s
    }

    .btn {
        border-radius: 2px;
        text-transform: capitalize;
        font-size: 15px;
        padding: 10px 19px;
        cursor: pointer
    }

    #infoMessage p{

        color: red !important;
    }


    .btn-google {
        color: #545454;
        background-color: #ffffff;
        box-shadow: 0 1px 2px 1px #ddd;
    }


    .or-container {
        align-items: center;
        color: #ccc;
        display: flex;
        margin: 25px 0;
    }

    .line-separator {
        background-color: #ccc;
        flex-grow: 5;
        height: 1px;
    }

    .or-label {
        flex-grow: 1;
        margin: 0 15px;
        text-align: center;
    }
</style>


</body>

</html>