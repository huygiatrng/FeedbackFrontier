<?php
// Start session
require 'config.php';
if(isset($_SESSION['user_id'])){
    header('Location: ../user/user_dashboard.php');
    exit;
}
require 'google-api/vendor/autoload.php';

ini_set('log_errors', 'On');
ini_set('error_log', 'C:/xampp/htdocs/course/public/authentication/googleLogin/php-errors.log');

error_reporting(E_ALL);



// Creating new google client instance
$client = new Google_Client();

// Enter your Client ID
$client->setClientId('/*YOUR_GOOGLE_CLIENT_ID*/');

// Enter your Client Secrect
$client->setClientSecret('/*YOUR_GOOGLE_CLIENT_SECRET*/');

// Enter the Redirect URL
$client->setRedirectUri('http://localhost/course/public/authentication/GoogleLogin.php');

// Adding those scopes which we want to get (email & profile Information)
$client->addScope("email");
$client->addScope("profile");

if(isset($_GET['code'])):
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if(!isset($token["error"])){
        $client->setAccessToken($token['access_token']);

        // getting profile information
        $google_oauth = new Google_Service_Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();

        // Storing data into database
        $id = mysqli_real_escape_string($db_connection, $google_account_info->id);
        $full_name = explode(' ', mysqli_real_escape_string($db_connection, trim($google_account_info->name)), 2);  // Splitting name into first name and last name
        $first_name = $full_name[0];
        $last_name = !empty($full_name[1]) ? $full_name[1] : '';
        $email = mysqli_real_escape_string($db_connection, $google_account_info->email);
        $password = password_hash('default_password', PASSWORD_BCRYPT); // setting a default password, you can change it as needed
        $role = 'user'; // setting the role to 'user'
        $school_id = null; // setting the school_id to null

        // checking user already exists or not
        $get_user = mysqli_query($db_connection, "SELECT `user_id` FROM `Users` WHERE `google_id`='$id'");

        if(mysqli_num_rows($get_user) > 0){
            $user_data = mysqli_fetch_assoc($get_user);
            $_SESSION['user_id'] = $user_data['user_id'];
            $_SESSION['role'] = $user_data['role'];
            header('Location: ../user/user_dashboard.php');
            exit;
        }
        else{
            // if user not exists we will insert the user
            echo 'Preparing to insert user...';

            $stmt = $db_connection->prepare('INSERT INTO Users (google_id, first_name, last_name, email, password, role, school_id) VALUES (?, ?, ?, ?, ?, ?, ?)');

            if ($stmt) {
                $stmt->bind_param('ssssssi', $id, $first_name, $last_name, $email, $password, $role, $school_id);

                echo 'Executing query...';
                if($stmt->execute()){
                    echo 'Query executed successfully...';
                    $last_id = $stmt->insert_id; // get the last inserted id
                    $_SESSION['user_id'] = $last_id;
                    $_SESSION['role'] = $role;
                    header('Location: ../user/user_dashboard.php');
                    exit;
                }
                else {
                    // Debugging: Check for any SQL errors
                    echo "Error: " . $stmt->error;
                }
            } else {
                // Debugging: Check for any SQL errors
                echo "Error: " . $db_connection->error;
            }

        }



    }
    else{
        header('Location: login.php');
        exit;
    }

else:
    // Google Login Url = $client->createAuthUrl(); 
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Login - LaravelTuts</title>
        <style>
            *,
            *::before,
            *::after {
                box-sizing: border-box;
                -webkit-box-sizing: border-box;
            }
            body{
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: #f7f7ff;
                padding: 10px;
                margin: 0;
            }
            ._container{
                max-width: 400px;
                background-color: #ffffff;
                padding: 20px;
                margin: 0 auto;
                border: 1px solid #cccccc;
                border-radius: 2px;
            }
            ._container.btn{
                text-align: center;
            }
            .heading{
                text-align: center;
                color: #4d4d4d;
                text-transform: uppercase;
            }
            .login-with-google-btn {
                transition: background-color 0.3s, box-shadow 0.3s;
                padding: 12px 16px 12px 42px;
                border: none;
                border-radius: 3px;
                box-shadow: 0 -1px 0 rgb(0 0 0 / 4%), 0 1px 1px rgb(0 0 0 / 25%);
                color: #ffffff;
                font-size: 14px;
                font-weight: 500;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Fira Sans", "Droid Sans", "Helvetica Neue", sans-serif;
                background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTgiIGhlaWdodD0iMTgiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj48cGF0aCBkPSJNMTcuNiA5LjJsLS4xLTEuOEg5djMuNGg0LjhDMTMuNiAxMiAxMyAxMyAxMiAxMy42djIuMmgzYTguOCA4LjggMCAwIDAgMi42LTYuNnoiIGZpbGw9IiM0Mjg1RjQiIGZpbGwtcnVsZT0ibm9uemVybyIvPjxwYXRoIGQ9Ik05IDE4YzIuNCAwIDQuNS0uOCA2LTIuMmwtMy0yLjJhNS40IDUuNCAwIDAgMS04LTIuOUgxVjEzYTkgOSAwIDAgMCA4IDV6IiBmaWxsPSIjMzRBODUzIiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48cGF0aCBkPSJNNCAxMC43YTUuNCA1LjQgMCAwIDEgMC0zLjRWNUgxYTkgOSAwIDAgMCAwIDhsMy0yLjN6IiBmaWxsPSIjRkJCQzA1IiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48cGF0aCBkPSJNOSAzLjZjMS4zIDAgMi41LjQgMy40IDEuM0wxNSAyLjNBOSA5IDAgMCAwIDEgNWwzIDIuNGE1LjQgNS40IDAgMCAxIDUtMy43eiIgZmlsbD0iI0VBNDMzNSIgZmlsbC1ydWxlPSJub256ZXJvIi8+PHBhdGggZD0iTTAgMGgxOHYxOEgweiIvPjwvZz48L3N2Zz4=);
                background-color: #4a4a4a;
                background-repeat: no-repeat;
                background-position: 12px 11px;
                text-decoration: none;
            }
            .login-with-google-btn:hover {
                box-shadow: 0 -1px 0 rgba(0, 0, 0, 0.04), 0 2px 4px rgba(0, 0, 0, 0.25);
            }
            .login-with-google-btn:active {
                background-color: #000000;
            }
            .login-with-google-btn:focus {
                outline: none;
                box-shadow: 0 -1px 0 rgba(0, 0, 0, 0.04), 0 2px 4px rgba(0, 0, 0, 0.25), 0 0 0 3px #c8dafc;
            }
            .login-with-google-btn:disabled {
                filter: grayscale(100%);
                background-color: #ebebeb;
                box-shadow: 0 -1px 0 rgba(0, 0, 0, 0.04), 0 1px 1px rgba(0, 0, 0, 0.25);
                cursor: not-allowed;
            }
        </style>
    </head>
    <body>
    <div class="_container">
        <h2 class="heading">Login</h2>
    </div>
    <div class="_container btn">

        <a type="button" class="login-with-google-btn" href="<?php echo $client->createAuthUrl(); ?>">
            Sign in with Google
        </a>
    </div>
    </body>
    </html>
<?php endif; ?>