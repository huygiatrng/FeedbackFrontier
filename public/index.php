<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <style>
        body{
            margin: 0px;
            padding: 0px;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('../assets/img/students.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            position: relative;
        }
    
        .overlay {
            background-color: rgba(0, 0, 0, 0.5);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
    </style>
    <title>Course Feedback System</title>
</head>

<body>
    <div class="container-fluid">
        <div class="landing">
            <div class="overlay">
                <h1 class="text-light text-center">Welcome to Course Feedback System</h1>
                <h6 class="text-light text-center">Share your valuable feedback to improve our courses. Sign in or create an account below to continue.</h6>
                <div class="text-center">
                   <a href="authentication/login.php" type="button" class="btn btn-primary mx-2">LOGIN</a>
                   <a href="authentication/register.php" type="button" class="btn btn-info">SIGNUP</a>
                   
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>