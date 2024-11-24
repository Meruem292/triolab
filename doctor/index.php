<?php

require "db.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

$doctor_id = $_SESSION['user_id'];

?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="green">

<head>

    <meta charset="utf-8" />
    <title>Triolab - Online Healthcare Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="assets/images/logo.png" type="image/png">

    <link href="assets/libs/swiper/swiper-bundle.min.css" rel="stylesheet" />

    <!-- Layout config Js -->
    <script src="assets/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />

</head>

<body style="background-color: #F2FFF1">

    <!-- Begin page -->
    <div id="layout-wrapper">

        <!-- HEADER -->
        <?php require "header.php"; ?>
        <?php require "functions.php"; ?>

        <!-- SIDEBAR -->
        <?php require "sidebar.php" ?>
        <div class="vertical-overlay"></div>

        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12" style="width:80%">
                            
                        </div>
                    </div>

                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <!-- FOOTER -->
            <?php require "footer.php"; ?>
        </div>
    </div>

    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->

    <!-- JAVASCRIPT -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="assets/js/plugins.js"></script>

    <!-- apexcharts -->
    <script src="assets/libs/apexcharts/apexcharts.min.js"></script>

    <!-- Swiper Js -->
    <script src="assets/libs/swiper/swiper-bundle.min.js"></script>

    <!-- CRM js -->
    <script src="assets/js/pages/dashboard-crypto.init.js"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>

    <!-- calendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.15/index.global.min.js'></script>




    <script>
        // Data for monthly sales
        var salesData = [{
                x: new Date(2024, 0, 1),
                y: 200
            },
            {
                x: new Date(2024, 1, 1),
                y: 300
            },
            {
                x: new Date(2024, 2, 1),
                y: 250
            },
            {
                x: new Date(2024, 3, 1),
                y: 400
            },
            {
                x: new Date(2024, 4, 1),
                y: 450
            },
            {
                x: new Date(2024, 5, 1),
                y: 300
            },
            {
                x: new Date(2024, 6, 1),
                y: 280
            },
            {
                x: new Date(2024, 7, 1),
                y: 300
            },
            {
                x: new Date(2024, 8, 1),
                y: 350
            },
            {
                x: new Date(2024, 9, 1),
                y: 500
            },
            {
                x: new Date(2024, 10, 1),
                y: 600
            },
            {
                x: new Date(2024, 11, 1),
                y: 550
            }
        ];

        // Create the chart options
        var options = {
            chart: {
                type: 'line',
                height: 350,
                animations: {
                    enabled: true,
                    easing: 'linear',
                    dynamicAnimation: {
                        speed: 2000
                    }
                }
            },
            series: [{
                name: 'Sales',
                data: salesData
            }],
            xaxis: {
                type: 'datetime',
                labels: {
                    format: 'MMM yyyy'
                }
            },
            yaxis: {
                title: {
                    text: 'Sales'
                }
            }
        };

        // Create the chart
        var chart = new ApexCharts(document.querySelector("#sales_chart"), options);

        // Render the chart
        chart.render();
    </script>
</body>

</html>