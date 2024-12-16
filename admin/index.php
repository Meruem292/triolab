<?php

require "db.php";
session_start();
include "functions.php";

if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

$admin_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT 
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
            appointment_count > 0
        ORDER BY 
            appointment_count DESC");
    $stmt->execute();
    $topServices = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching data: " . $e->getMessage());
}

// Retrieve summary statistics
$totalPatients = getTotalPatients($pdo);
$totalAppointments = getTotalAppointments($pdo);
$totalDoctors = getTotalDoctors($pdo);
$totalCompletedAppointments = getCompletedAppointments($pdo);
$totalAppointmentSlots = getTotalAppointmentSlots($pdo);
$totalPendingAppointments = getTotalPendingAppointments($pdo);
$totalServices = getTotalServices($pdo);

?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="green">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Triolab - Online Healthcare Management System</title>
    <link rel="shortcut icon" href="assets/images/logo.png" type="image/png">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css">

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="assets/js/layout.js"></script>

    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</head>

<body style="background-color: #F2FFF1">

    <div id="layout-wrapper">

        <!-- HEADER -->
        <?php require "header.php"; ?>

        <!-- SIDEBAR -->
        <?php require "sidebar.php"; ?>
        <div class="vertical-overlay"></div>

        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <!-- Page Title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                                <h4 class="mb-sm-0">Dashboard</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Dashboard Widgets -->
                    <div class="row">
                        <div class="col-xxl-4">
                            <div class="card card-height-100">
                                <div class="card-header border-0 align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Top Laboratory Services</h4>
                                    <select id="timelineSelect" class="form-select w-auto ms-3">
                                        <option value="daily">Daily</option>
                                        <option value="weekly">Weekly</option>
                                        <option value="monthly">Monthly</option>
                                        <option value="yearly">Yearly</option>
                                    </select>
                                </div>
                                <div class="card-body">
                                    <canvas id="doughnutChart" width="400" height="400"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-xxl-8 order-xxl-0 order-first">
                            <div class="d-flex flex-column h-100">

                                <div class="row mb-1">
                                    <?php 
                                    $stats = [
                                        ['icon' => 'ri-calendar-check-line', 'label' => 'NEW APPOINTMENTS', 'value' => $totalPendingAppointments],
                                        ['icon' => 'ri-stack-line', 'label' => 'TOTAL SERVICES', 'value' => $totalServices],
                                        ['icon' => 'ri-checkbox-circle-line', 'label' => 'COMPLETED APPOINTMENTS', 'value' => $totalCompletedAppointments],
                                        ['icon' => 'ri-group-line', 'label' => 'TOTAL PATIENTS', 'value' => $totalPatients],
                                        ['icon' => 'ri-time-line', 'label' => 'APPOINTMENT SLOTS', 'value' => $totalAppointmentSlots],
                                        ['icon' => 'ri-user-3-line', 'label' => 'TOTAL DOCTORS', 'value' => $totalDoctors]
                                    ];
                                    foreach ($stats as $stat) : ?>
                                        <div class="col-lg-6 mb-1">
                                            <div class="card h-90">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-xl flex-shrink-0">
                                                            <span class="avatar-title bg-light text-primary rounded-circle fs-1 material-shadow">
                                                                <i class="<?= $stat['icon']; ?> align-middle"></i>
                                                            </span>
                                                        </div>
                                                        <div class="flex-grow-1 ms-4 text-end">
                                                            <p class="text-uppercase fw-semibold fs-16 text mb-1"><?= $stat['label']; ?></p>
                                                            <h4 class="mb-0"><span class="counter-value" data-target="<?= $stat['value']; ?>">0</span></h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>

    <!-- FOOTER -->
    <?php require "footer.php"; ?>

    <!-- Back-to-Top Button -->
    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>

    <!-- JavaScript Libraries -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/plugins.js"></script>

    <!-- Chart Initialization -->
    <script>
        let chart = null;

        async function fetchChartData(timeline) {
            try {
                const response = await fetch(`getData.php?timeline=${timeline}`);
                if (!response.ok) throw new Error("Failed to fetch chart data");
                return await response.json();
            } catch (error) {
                console.error("Error fetching chart data:", error);
                return null;
            }
        }

        async function updateChart(timeline) {
            const chartData = await fetchChartData(timeline);
            if (chartData) {
                const { labels, values, total } = chartData;
                if (chart) {
                    chart.data.labels = labels;
                    chart.data.datasets[0].data = values;
                    chart.options.plugins.tooltip.callbacks.label = function (tooltipItem) {
                        const value = tooltipItem.raw;
                        const percentage = ((value / total) * 100).toFixed(2);
                        return `${tooltipItem.label}: ${value} (${percentage}%)`;
                    };
                    chart.update();
                }
            }
        }

        async function initChart() {
            const initialData = await fetchChartData('daily');
            if (initialData) {
                const { labels, values, total } = initialData;

                const data = {
                    labels: labels,
                    datasets: [{
                        label: 'Appointment Count',
                        data: values,
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

                const config = {
                    type: 'doughnut',
                    data: data,
                    options: {
                        responsive: true,
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function (tooltipItem) {
                                        const value = tooltipItem.raw;
                                        const percentage = ((value / total) * 100).toFixed(2);
                                        return `${tooltipItem.label}: ${value} (${percentage}%)`;
                                    }
                                }
                            },
                            legend: { position: 'top' },
                        }
                    }
                };

                const ctx = document.getElementById('doughnutChart').getContext('2d');
                chart = new Chart(ctx, config);
            }
        }

        document.getElementById('timelineSelect').addEventListener('change', (e) => {
            updateChart(e.target.value);
        });

        initChart();
    </script>

</body>

</html>
