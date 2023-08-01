<?php $projectName = "" ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title ?></title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/<?php echo $projectName ?>assets/plugins/fontawesome-free/css/all.min.css">

    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="/<?php echo $projectName ?>assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="/<?php echo $projectName ?>assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="/<?php echo $projectName ?>assets/plugins/jqvmap/jqvmap.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="/<?php echo $projectName ?>assets/css/index.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="/<?php echo $projectName ?>assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="/<?php echo $projectName ?>assets/plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="/<?php echo $projectName ?>assets/plugins/summernote/summernote-bs4.min.css">
</head>
<?php
if (!isset($_SESSION)) {
    session_start();
}

$homeURL = "#";
$current_page = basename($_SERVER['SCRIPT_NAME']);
$isAdmin = false;

if (isset($_SESSION['role'])) {
    switch ($_SESSION['role']) {
        case 'user':
            $homeURL = "/" . $projectName . "public/user/user_dashboard.php";
            $profileURL = "/" . $projectName . "public/user/profile/user_profile_update.php";
            break;
        case 'instructor':
            $homeURL = "/" . $projectName . "public/instructor/instructor_dashboard.php";
            $profileURL = "/" . $projectName . "public/instructor/profile/instructor_profile_update.php";

            break;
        case 'admin':
            $homeURL = "/" . $projectName . "public/admin/admin_dashboard.php";
            $isAdmin = true;
            break;
    }
} else {
    if ($current_page == "login.php" || $current_page == "register.php") {
        $homeURL = "/" . $projectName . "public/index.php";
    } else {
        $homeURL = "/" . $projectName . "public/index.php";
    }
}
?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="/<?php echo $projectName ?>assets/img/826.gif" alt="logo" height="60" width="60">
        </div>
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>

            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/<?php echo $projectName ?>public/authentication/logout.php" role="button" title="Log Out">
                        Log out <i class="ml-1 fas fa-sign-out-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-light-indigo elevation-4">
            <!-- Brand Logo -->
            <a href="<?php $homeURL ?>" class="brand-link">
                <!-- <img src="assets/img/coursefeedback.png" alt="coursefeedback Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
                <span class="brand-text font-weight-light"><b>Course Feedback</b></span>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column " data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item ">
                            <a href="<?php echo $homeURL ?>" class="nav-link">
                                <i class="nav-icon fa fa-home" aria-hidden="true"></i>
                                <p>Home</p>
                            </a>
                        </li>
                        <?php if ($isAdmin) : ?>
                            <li class="nav-item ">
                                <a href="/<?php echo $projectName ?>public/admin/manage_courses.php" class="nav-link" data-menu="course">
                                    <i class="nav-icon fa fa-book" aria-hidden="true"></i>
                                    <p>Manage Courses</p>
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a href="/<?php echo $projectName ?>public/admin/manage_users.php" class="nav-link" data-menu="user">
                                    <i class="nav-icon fas fa-user-friends" aria-hidden="true"></i>
                                    <p>Manage Users</p>
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a href="/<?php echo $projectName ?>public/admin/manage_schools.php" class="nav-link" data-menu="school">
                                    <i class="nav-icon fa fa-school" aria-hidden="true"></i>
                                    <p>Manage Schools</p>
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a href="/<?php echo $projectName ?>public/admin/manage_feedback.php" class="nav-link" data-menu="feedback">
                                    <i class="nav-icon fa fa-quote-left" aria-hidden="true"></i>
                                    <p>Manage Feedback</p>
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a href="/<?php echo $projectName ?>public/admin/manage_reports.php" class="nav-link" data-menu="course">
                                    <i class="nav-icon fa fa-flag" aria-hidden="true"></i>
                                    <p>Manage Reports</p>
                                </a>
                            </li>
                        <?php endif ?>
                        <?php if (!$isAdmin) : ?>
                            <li class="nav-item ">
                                <a href="<?php echo $profileURL ?>" class="nav-link" data-menu="profile">
                                    <i class="nav-icon fa fa-user" aria-hidden="true"></i>
                                    <p>Profile</p>
                                </a>
                            </li>
                        <?php endif ?>
                        <!-- <li class="nav-header">EXAMPLES</li> -->
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-12">
                            <h1 class="m-0"><?php if (isset($pageHeading)) {
                                                echo $pageHeading;
                                            } ?></h1>
                        </div><!-- /.col -->
                        <!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <?php echo $content; ?>
                </div><!-- /.container-fluid -->
                <?php if (isset($modalContent)) {
                    echo $modalContent;
                } ?>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2023 <a href="#">Course Feedback System</a>.</strong>
            All rights reserved.

        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="/<?php echo $projectName ?>assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/<?php echo $projectName ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="/<?php echo $projectName ?>assets/js/index.js"></script>
    <script src="/<?php echo $projectName ?>assets/plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- ChartJS -->
    <script src="/<?php echo $projectName ?>assets/plugins/chart.js/Chart.min.js"></script>
    <!-- JQVMap -->
    <script src="/<?php echo $projectName ?>assets/plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="/<?php echo $projectName ?>assets/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="/<?php echo $projectName ?>assets/plugins/moment/moment.min.js"></script>
    <script src="/<?php echo $projectName ?>assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Summernote -->
    <script src="/<?php echo $projectName ?>assets/plugins/summernote/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="/<?php echo $projectName ?>assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <script>
        $(document).ready(function() {
            // Get the current URL path
            var currentPath = window.location.pathname;
            var parts = currentPath.split('/');
            var lastPart = parts.pop();
            $('.nav-link').removeClass('active');
            $('.nav-link').each(function() {
                var href = $(this).attr('href');
                var word = $(this).data('menu');
                if (href == currentPath) {
                    $(this).addClass('active'); // Add the active class
                    return false; // Exit the loop since we found a match
                } else if (lastPart.includes(word)) {
                    $(this).addClass('active'); // Add the active class
                    return false;
                }
            });
        });

        function deleteAlert(e) {
            e.preventDefault();
            deleteUrl = e.currentTarget.href;
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = deleteUrl;
                }
            })
        }
    </script>
    <?php
    if (isset($pageJs)) {
        echo $pageJs;
    }
    ?>
</body>

</html>