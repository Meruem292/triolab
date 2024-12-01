<?php

require "db.php";
session_start();
include "functions.php";

$admin_id = $_SESSION['user_id'];
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
};

try {
    // Query to count appointments per service
    $stmt = $pdo->prepare("
        SELECT 
            s.service AS service_name, 
            COUNT(a.id) AS appointment_count
        FROM 
            appointment AS a
        INNER JOIN 
            services AS s ON a.service_id = s.id
        WHERE 
            a.is_archive = 0 AND s.is_archive = 0
        GROUP BY 
            s.service
        HAVING 
            appointment_count > 0  -- Filter services with no appointments
        ORDER BY 
            appointment_count DESC
    ");
    $stmt->execute();
    $topServices = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching data: " . $e->getMessage());
}


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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
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
                                </div><!-- end cardheader -->
                                <div class="card-body">
                                    <canvas id="doughnutChart" width="400" height="400"></canvas>
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
    <?php
    try {
        // Query to count appointments grouped by services
        $query = "
            SELECT s.service AS service_name, COUNT(a.id) AS appointment_count
            FROM appointment a
            JOIN services s ON a.service_id = s.id
            WHERE a.is_archive = 0
            GROUP BY a.service_id
        ";

        $stmt = $pdo->query($query);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Prepare labels, values, and calculate total
        $labels = array_column($data, 'service_name');
        $values = array_column($data, 'appointment_count');
        $total = array_sum($values);

        // Calculate percentages
        $percentages = array_map(function ($value) use ($total) {
            return round(($value / $total) * 100, 2);
        }, $values);
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
    ?>



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

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        // Prepare chart data
        const data = {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                label: 'Appointment Count',
                data: <?php echo json_encode($values); ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        };

        // Render doughnut chart
        const config = {
            type: 'doughnut',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                const value = tooltipItem.raw; // Appointment count
                                const total = <?php echo $total; ?>; // Total appointments
                                const percentage = ((value / total) * 100).toFixed(2); // Calculate percentage
                                return `${tooltipItem.label}: ${value} (${percentage}%)`;
                            }
                        }
                    },
                    legend: {
                        position: 'top',
                    },
                }
            },
        };

        // Initialize Chart.js
        const ctx = document.getElementById('doughnutChart').getContext('2d');
        new Chart(ctx, config);
    </script>


</body>

</html>