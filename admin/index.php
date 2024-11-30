<?php

require "db.php";
session_start();
include "functions.php";

$admin_id = $_SESSION['user_id'];
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
};


$totalPatients = getTotalPatients($pdo);
$totalAppointments = getTotalAppointments($pdo);
$totalDoctors = getTotalDoctors($pdo);
$totalComepletedAppointments = getCompletedAppointments($pdo);
$totalAppointmentSlots = getTotalAppointmentSlots($pdo);
$totalPendingAppointments = getTotalPendingAppointments($pdo);

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
    <style>
        body {
            overflow: hidden;
            /* Hides any overflowing content */
        }
    </style>
</head>

<body style="background-color: #F2FFF1">

    <!-- Begin page -->
    <div id="layout-wrapper">

        <!-- HEADER -->
        <?php require "header.php"; ?>

        <!-- SIDEBAR -->
        <?php require "sidebar.php" ?>
        <div class="vertical-overlay"></div>

        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                                <h4 class="mb-sm-0">Dashboard</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xxl-4">
                            <div class="card card-height-100">
                                <div class="card-header border-0 align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Top Laboratory Services</h4>
                                    <div>
                                        <select class="form-select">
                                            <option value="Weekly">Weekly</option>
                                            <option value="Monthly">Monthly</option>
                                            <option value="Yearly">Yearly</option>
                                        </select>
                                    </div>
                                </div><!-- end cardheader -->
                                <div class="card-body">
                                    <div id="portfolio_donut_charts" data-colors='["--vz-primary", "--vz-info", "--vz-warning", "--vz-success"]' data-colors-minimal='["--vz-primary", "--vz-primary-rgb, 0.85", "--vz-primary-rgb, 0.65", "--vz-primary-rgb, 0.50"]' data-colors-interactive='["--vz-primary", "--vz-primary-rgb, 0.85", "--vz-primary-rgb, 0.65", "--vz-primary-rgb, 0.50"]' data-colors-corporate='["--vz-primary", "--vz-secondary", "--vz-info", "--vz-success"]' data-colors-galaxy='["--vz-primary", "--vz-primary-rgb, 0.85", "--vz-primary-rgb, 0.65", "--vz-primary-rgb, 0.50"]' class="apex-charts" dir="ltr"></div>

                                    <ul class="list-group list-group-flush border-dashed mb-0 mt-3 pt-2">
                                        <li class="list-group-item px-0">
                                            <div class="d-flex">
                                                <div class="flex-grow-1 ms-2">
                                                    <p class="fs-12 mb-0 text-muted"><i class="mdi mdi-circle fs-10 align-middle text-primary me-1"></i>CBC </p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item px-0">
                                            <div class="d-flex">
                                                <div class="flex-grow-1 ms-2">
                                                    <p class="fs-12 mb-0 text-muted"><i class="mdi mdi-circle fs-10 align-middle text-success me-1"></i>Pre-Employment </p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item px-0">
                                            <div class="d-flex">
                                                <div class="flex-grow-1 ms-2">
                                                    <p class="fs-12 mb-0 text-muted"><i class="mdi mdi-circle fs-10 align-middle text-info me-1"></i>X-Ray </p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item px-0">
                                            <div class="d-flex">
                                                <div class="flex-grow-1 ms-2">
                                                    <p class="fs-12 mb-0 text-muted"><i class="mdi mdi-circle fs-10 align-middle text-warning me-1"></i>ECG </p>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-xxl-8 order-xxl-0 order-first">
                            <div class="d-flex flex-column h-100">
                                <div class="row mb-1">
                                    <div class="col-lg-6">
                                        <div class="card h-90">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <!-- Logo Section -->
                                                    <div class="avatar-xl flex-shrink-0">
                                                        <span class="avatar-title bg-light text-primary rounded-circle fs-1 material-shadow">
                                                            <i class="ri-calendar-check-line align-middle"></i> <!-- Icon for Appointments -->
                                                        </span>
                                                    </div>
                                                    <!-- Text Content -->
                                                    <div class="flex-grow-1 ms-4 text-end">
                                                        <p class="text-uppercase fw-semibold fs-16 text mb-1">NEW APPOINTMENTS</p>
                                                        <h4 class="mb-0"><span class="counter-value" data-target="<?= $totalPendingAppointments ?>">0</span></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="card h-90">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <!-- Logo Section -->
                                                    <div class="avatar-xl flex-shrink-0">
                                                        <span class="avatar-title bg-light text-primary rounded-circle fs-1 material-shadow">
                                                            <i class="ri-stack-line align-middle"></i> <!-- Icon for Total Appointments -->
                                                        </span>
                                                    </div>
                                                    <!-- Text Content -->
                                                    <div class="flex-grow-1 ms-4 text-end">
                                                        <p class="text-uppercase fw-semibold fs-16 text mb-1">TOTAL APPOINTMENTS</p>
                                                        <h4 class="mb-0"><span class="counter-value" data-target="<?= $totalAppointments; ?>">0</span></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-lg-6">
                                        <div class="card h-90">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <!-- Logo Section -->
                                                    <div class="avatar-xl flex-shrink-0">
                                                        <span class="avatar-title bg-light text-primary rounded-circle fs-1 material-shadow">
                                                            <i class="ri-checkbox-circle-line align-middle"></i> <!-- Icon for Completed Appointments -->
                                                        </span>
                                                    </div>
                                                    <!-- Text Content -->
                                                    <div class="flex-grow-1 ms-4 text-end">
                                                        <p class="text-uppercase fw-semibold fs-16 text mb-1">COMPLETED APPOINTMENTS</p>
                                                        <h4 class="mb-0"><span class="counter-value" data-target="<?= $totalComepletedAppointments; ?>">0</span></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="card h-90">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <!-- Logo Section -->
                                                    <div class="avatar-xl flex-shrink-0">
                                                        <span class="avatar-title bg-light text-primary rounded-circle fs-1 material-shadow">
                                                            <i class="ri-group-line align-middle"></i> <!-- Icon for Total Patients -->
                                                        </span>
                                                    </div>
                                                    <!-- Text Content -->
                                                    <div class="flex-grow-1 ms-4 text-end">
                                                        <p class="text-uppercase fw-semibold fs-16 text mb-1">TOTAL PATIENTS</p>
                                                        <h4 class="mb-0"><span class="counter-value" data-target="<?= $totalPatients; ?>">0</span></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-lg-6">
                                        <div class="card h-90">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <!-- Logo Section -->
                                                    <div class="avatar-xl flex-shrink-0">
                                                        <span class="avatar-title bg-light text-primary rounded-circle fs-1 material-shadow">
                                                            <i class="ri-time-line align-middle"></i> <!-- Icon for Appointment Slots -->
                                                        </span>
                                                    </div>
                                                    <!-- Text Content -->
                                                    <div class="flex-grow-1 ms-4 text-end">
                                                        <p class="text-uppercase fw-semibold fs-16 text mb-1">APPOINTMENT SLOTS</p>
                                                        <h4 class="mb-0"><span class="counter-value" data-target="<?= $totalAppointmentSlots; ?>">0</span></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="card h-90">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <!-- Logo Section -->
                                                    <div class="avatar-xl flex-shrink-0">
                                                        <span class="avatar-title bg-light text-primary rounded-circle fs-1 material-shadow">
                                                            <i class="ri-user-3-line align-middle"></i> <!-- Icon for Total Doctors -->
                                                        </span>
                                                    </div>
                                                    <!-- Text Content -->
                                                    <div class="flex-grow-1 ms-4 text-end">
                                                        <p class="text-uppercase fw-semibold fs-16 text mb-1">TOTAL DOCTORS</p>
                                                        <h4 class="mb-0"><span class="counter-value" data-target="<?= $totalDoctors; ?>">0</span></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- End Page-content -->

    <!-- FOOTER -->
    <?php require "footer.php"; ?>
   
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