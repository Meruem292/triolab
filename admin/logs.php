<?php
require "db.php";

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit;
}

$admin_id = $_SESSION['user_id'];
?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="green">

<head>
    <meta charset="utf-8" />
    <title>Triolab - Online Healthcare Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/images/logo.png" type="image/png">

    <!-- External CSS links -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="assets/css/sweetalert.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css">

    <!-- jQuery (required for DataTables) -->
    

    <!-- Prevents page reload on refresh -->
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</head>

<body style="background-color: #F2FFF1">

    <div id="layout-wrapper">
        <?php require "header.php"; ?>
        <?php require "sidebar.php"; ?>
        <?php require "functions.php"; ?>

        <div class="vertical-overlay"></div>

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                                <h4 class="mb-sm-0">Logs</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                        <li class="breadcrumb-item active">Logs</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mb-2 mt-3">
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <h4>LOGS</h4>
                                                <?php
                                                $table = 'logs';
                                                $columns = array('id', 'action', 'timestamp', 'details');
                                                $displayImageColumns = array();
                                                // Call displayTable function without reinitializing empty array
                                                displayTable($pdo, $table, $columns, $displayImageColumns, false, '', false);
                                                ?>
                                            </div>

                                        </div>
                                    </div>
                                </div> <!-- end card-body -->
                            </div>
                        </div>
                    </div>
                </div>
                <?php require "footer.php"; ?>
            </div>
        </div>

        <!-- Button to go to top -->
        <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
            <i class="ri-arrow-up-line"></i>
        </button>

        <!-- External JS libraries -->
        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/libs/simplebar/simplebar.min.js"></script>
        <script src="assets/libs/node-waves/waves.min.js"></script>
        <script src="assets/libs/feather-icons/feather.min.js"></script>
        <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
        <script src="assets/js/plugins.js"></script>
        <script src="assets/js/pages/listjs.init.js"></script>
        <script src="assets/js/app.js"></script>
        <script src="assets/js/sweetalert.js"></script>

        <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>

        <script>
            $(document).ready(function() {
                $('#myTable').DataTable(); // Initializes the DataTable functionality
            });
        </script>

        <!-- Show SweetAlert message -->
        <?php if (isset($_SESSION['message']) && isset($_SESSION['status'])) { ?>
            <script>
                Swal.fire({
                    text: "<?php echo $_SESSION['message']; ?>",
                    icon: "<?php echo $_SESSION['status']; ?>",
                });
            </script>
        <?php
            unset($_SESSION['message']);
            unset($_SESSION['status']);
        } ?>
</body>

</html>