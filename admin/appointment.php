<?php

require "db.php";
session_start();

$admin_id = $_SESSION['user_id'];
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
};

if (isset($_POST['archive_appointment'])) {
    $appointmentIdDelete = $_POST['appointmentIdDelete'];
    $archive = '1';

    // Update the service information in the database
    $archiveQuery = $pdo->prepare("UPDATE appointment SET is_archive = :archive WHERE app_id = :app_id");
    $archiveQuery->bindParam(':archive', $archive);
    $archiveQuery->bindParam(':app_id', $appointmentIdDelete);

    if ($archiveQuery->execute()) {
        $_SESSION['message'] = "Appointment archived successfully.";
        $_SESSION['status'] = "success";
    } else {
        $_SESSION['message'] = "Error archiving appointment.";
        $_SESSION['status'] = "error";
    }
}

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
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</head>

<body style="background-color: #F2FFF1">

    <!-- Begin page -->
    <div id="layout-wrapper">

        <!-- HEADER -->
        <?php require "header.php"; ?>

        <!-- SIDEBAR -->
        <?php require "sidebar.php" ?>
        <?php include "functions.php"; ?>
        <div class="vertical-overlay"></div>

        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                                <h4 class="mb-sm-0">Appointment</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                        <li class="breadcrumb-item active">Appointment</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-12">
                            <div class="card">

                                <div class="card-body">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAppointmentModal">
                                        + Add New Appointment
                                    </button>
                                    <?php include "modals/appointment.php" ?>
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs nav-tabs-custom nav-success nav-justified mb-3" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#calendarTab" role="tab">
                                                CALENDAR OF APPOINTMENTS
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#new" role="tab">
                                                NEW/PENDING APPOINTMENTS
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#completed" role="tab">
                                                COMPLETED APPOINTMENTS
                                            </a>
                                        </li>
                                    </ul>
                                    <!-- Tab panes -->
                                    <div class="tab-content text-muted">
                                        <div class="tab-pane active" id="calendarTab" role="tabpanel">
                                            <div class="row">
                                                <div class="col-xxl-8">
                                                    <?php calendarMonthShowsAdmin(); ?>
                                                </div>
                                                <div class="col-xxl-4">
                                                    <?php calendarWeekShowsAdmin(); ?>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="tab-pane" id="new" role="tabpanel">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="d-flex justify-content-sm-start">
                                                    <div class="search-box ms-2 mt-3 mb-3">
                                                        <input type="text" id="searchInput" class="form-control" placeholder="Search for patients..." onkeyup="searchTable()">
                                                        <i class="ri-search-line search-icon"></i>
                                                    </div>
                                                </div>
                                                <div class="app-search d-none d-md-block">
                                                    <div class="position-relative">
                                                        <input id="datePicker" type="text" class="form-control" style="border: 1px solid #777;" placeholder="Filter by date" autocomplete="off">
                                                        <span class="mdi mdi-calendar search-widget-icon"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="listjs-table" id="customerList">
                                                <div class="table-responsive table-card mt-3 mb-1">
                                                    <table class="table align-middle table-nowrap" id="customerTable">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Time</th>
                                                                <th>Date</th>
                                                                <th>Patient Name</th>
                                                                <th>Service</th>
                                                                <th>Doctor</th>
                                                                <th>Service Status</th>
                                                                <th>Payment Status</th>
                                                                <th>Appointment Status</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="list">
                                                            <?php
                                                            $query = "
                                                                    SELECT 
                                                                        appointment.id AS id,
                                                                        appointment.app_id,
                                                                        patient.firstname AS patient_firstname,
                                                                        patient.lastname AS patient_lastname,
                                                                        GROUP_CONCAT(services.service SEPARATOR ', ') AS services,
                                                                        doctor.firstname AS doctor_firstname,
                                                                        doctor.lastname AS doctor_lastname,
                                                                        appointment.appointment_time,
                                                                        appointment.appointment_date,
                                                                        appointment.medical,
                                                                        appointment.status,
                                                                        appointment.paid,
                                                                        SUM(services.cost) AS total_service_cost  -- Sum of service costs for the services linked to the appointment
                                                                    FROM appointment
                                                                    INNER JOIN patient ON appointment.patient_id = patient.id
                                                                    INNER JOIN services ON appointment.service_id = services.id
                                                                    LEFT JOIN doctor ON appointment.doctor_id = doctor.employee_id
                                                                    WHERE appointment.is_archive = 0
                                                                    AND appointment.status = 'Pending'
                                                                    GROUP BY appointment.app_id
                                                                    ORDER BY appointment.date_added ASC;
                                                                ";

                                                            $appointments = $pdo->query($query);

                                                            if ($appointments->rowCount() > 0) {
                                                                while ($row = $appointments->fetch(PDO::FETCH_ASSOC)) {
                                                                    $formatted_time = date("g:i A", strtotime($row['appointment_time']));
                                                                    $formatted_date = date("F j, Y", strtotime($row['appointment_date']));

                                                                    $patientName = $row['patient_firstname'] . " " . $row['patient_lastname'];
                                                                    $doctorName = $row['doctor_firstname'] && $row['doctor_lastname']
                                                                        ? "Dr. " . $row['doctor_firstname'] . " " . $row['doctor_lastname']
                                                                        : "N/A";

                                                                    $medical = $row['medical'] ?: "N/A";
                                                                    $medicalStatusClass = match ($row['medical']) {
                                                                        'Pending' => 'bg-warning text-dark',
                                                                        'Approved' => 'bg-success text-white',
                                                                        default => 'bg-secondary text-white',
                                                                    };

                                                                    $services = $row['services'] ?: "N/A";
                                                                    $serviceStatusClass = match ($row['status']) {
                                                                        'Pending' => 'bg-warning text-dark',
                                                                        'Approved' => 'bg-success text-white',
                                                                        default => 'bg-secondary text-white',
                                                                    };

                                                                    $paymentStatusClass = match ($row['paid']) {
                                                                        'Pending' => 'bg-warning text-dark',
                                                                        'Approved' => 'bg-success text-white',
                                                                        'Disapproved' => 'bg-danger text-white',
                                                                        default => 'bg-secondary text-white',
                                                                    };
                                                            ?>
                                                                    <tr>
                                                                        <td class="time"><?= htmlspecialchars($formatted_time); ?></td>
                                                                        <td class="date"><?= htmlspecialchars($formatted_date); ?></td>
                                                                        <td class="patient_name"><?= htmlspecialchars($patientName); ?></td>
                                                                        <td class="service"><?= htmlspecialchars($services); ?></td>
                                                                        <td class="doctor"><?= htmlspecialchars($doctorName); ?></td>
                                                                        <td class="medical">
                                                                            <span class="badge <?= $medicalStatusClass; ?>"><?= htmlspecialchars($row['medical']); ?></span>
                                                                        </td>
                                                                        <td class="payment_status">
                                                                            <span class="badge <?= $paymentStatusClass; ?>"><?= htmlspecialchars($row['paid']); ?></span>
                                                                        </td>
                                                                        <td class="service_status">
                                                                            <span class="badge <?= $serviceStatusClass; ?>"><?= htmlspecialchars($row['status']); ?></span>
                                                                        </td>


                                                                        <td>
                                                                            <a href="#" class="btn btn-light btn-sm edit-btn"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#editAppointment"
                                                                                data-app-id="<?php echo $row['app_id']; ?>"
                                                                                data-service-cost="<?php echo $row['total_service_cost']; ?>"> <!-- Corrected data-service-cost -->
                                                                                <i class="ri-edit-fill align-bottom me-2 text-muted"></i> Update
                                                                            </a>

                                                                            <a href="#" class="btn btn-danger btn-sm archive-btn"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#archiveAppointment"
                                                                                data-appointment-id="<?= htmlspecialchars($row['app_id']); ?>">
                                                                                <i class="ri-delete-bin-fill align-bottom me-2"></i> Archive
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                <?php
                                                                }
                                                            } else {
                                                                ?>
                                                                <tr>
                                                                    <td colspan="8">
                                                                        <div class="noresult text-center">
                                                                            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json"
                                                                                trigger="loop"
                                                                                colors="primary:#121331,secondary:#08a88a"
                                                                                style="width:75px;height:75px">
                                                                            </lord-icon>
                                                                            <h5 class="mt-2">Sorry! No Result Found</h5>
                                                                            <p class="text-muted mb-0">We've searched in our database but did not find any data yet!</p>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="d-flex justify-content-end">
                                                    <div class="pagination-wrap hstack gap-2" style="display: flex;">
                                                        <a class="page-item pagination-prev disabled" href="javascript:void(0);">
                                                            Previous
                                                        </a>
                                                        <ul class="pagination listjs-pagination mb-0">
                                                            <li clas="active"><a class="btn btn-primary btn-sm" href="#">1</a></li>
                                                            <li clas=""><a class="btn btn-sm" style="border: 1px solid #e9ebec;" href="#">2</a></li>
                                                            <li clas=""><a class="btn btn-sm" style="border: 1px solid #e9ebec;" href="#">3</a></li>
                                                        </ul>
                                                        <a class="page-item pagination-next" href="javascript:void(0);">
                                                            Next
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="completed" role="tabpanel">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="d-flex justify-content-sm-start">
                                                    <div class="search-box ms-2 mt-3 mb-3">
                                                        <input type="text" id="searchInput" class="form-control" placeholder="Search for patients..." onkeyup="searchTable()">
                                                        <i class="ri-search-line search-icon"></i>
                                                    </div>
                                                </div>
                                                <div class="app-search d-none d-md-block">
                                                    <div class="position-relative">
                                                        <input id="datePicker" type="text" class="form-control" style="border: 1px solid #777;" placeholder="Filter by date" autocomplete="off">
                                                        <span class="mdi mdi-calendar search-widget-icon"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="listjs-table" id="customerList">
                                                <div class="table-responsive table-card mt-3 mb-1">
                                                    <table class="table align-middle table-nowrap" id="customerTable">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Time</th>
                                                                <th>Date</th>
                                                                <th>Patient Name</th>
                                                                <th>Service</th>
                                                                <th>Doctor</th>
                                                                <th>Service Status</th>
                                                                <th>Payment Status</th>
                                                                <th>Appointment Status</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="list">
                                                            <?php
                                                            $query = "
                                                                    SELECT 
                                                                        appointment.id AS id,
                                                                        appointment.app_id,
                                                                        patient.firstname AS patient_firstname,
                                                                        patient.lastname AS patient_lastname,
                                                                        GROUP_CONCAT(services.service SEPARATOR ', ') AS services,
                                                                        doctor.firstname AS doctor_firstname,
                                                                        doctor.lastname AS doctor_lastname,
                                                                        appointment.appointment_time,
                                                                        appointment.appointment_date,
                                                                        appointment.medical,
                                                                        appointment.status,
                                                                        appointment.paid,
                                                                        SUM(services.cost) AS total_service_cost  -- Sum of service costs for the services linked to the appointment
                                                                    FROM appointment
                                                                    INNER JOIN patient ON appointment.patient_id = patient.id
                                                                    INNER JOIN services ON appointment.service_id = services.id
                                                                    LEFT JOIN doctor ON appointment.doctor_id = doctor.employee_id
                                                                    WHERE appointment.is_archive = 0
                                                                    AND appointment.status = 'Completed'
                                                                    GROUP BY appointment.app_id
                                                                    ORDER BY appointment.date_added ASC;
                                                                ";

                                                            $appointments = $pdo->query($query);

                                                            if ($appointments->rowCount() > 0) {
                                                                while ($row = $appointments->fetch(PDO::FETCH_ASSOC)) {
                                                                    $formatted_time = date("g:i A", strtotime($row['appointment_time']));
                                                                    $formatted_date = date("F j, Y", strtotime($row['appointment_date']));

                                                                    $patientName = $row['patient_firstname'] . " " . $row['patient_lastname'];
                                                                    $doctorName = $row['doctor_firstname'] && $row['doctor_lastname']
                                                                        ? "Dr. " . $row['doctor_firstname'] . " " . $row['doctor_lastname']
                                                                        : "N/A";

                                                                    $medical = $row['medical'] ?: "N/A";
                                                                    $medicalStatusClass = match ($row['medical']) {
                                                                        'Pending' => 'bg-warning text-dark',
                                                                        'Approved' => 'bg-success text-white',
                                                                        default => 'bg-secondary text-white',
                                                                    };

                                                                    $services = $row['services'] ?: "N/A";
                                                                    $serviceStatusClass = match ($row['status']) {
                                                                        'Pending' => 'bg-warning text-dark',
                                                                        'Approved' => 'bg-success text-white',
                                                                        default => 'bg-secondary text-white',
                                                                    };

                                                                    $paymentStatusClass = match ($row['paid']) {
                                                                        'Pending' => 'bg-warning text-dark',
                                                                        'Approved' => 'bg-success text-white',
                                                                        'Disapproved' => 'bg-danger text-white',
                                                                        default => 'bg-secondary text-white',
                                                                    };
                                                            ?>
                                                                    <tr>
                                                                        <td class="time"><?= htmlspecialchars($formatted_time); ?></td>
                                                                        <td class="date"><?= htmlspecialchars($formatted_date); ?></td>
                                                                        <td class="patient_name"><?= htmlspecialchars($patientName); ?></td>
                                                                        <td class="service"><?= htmlspecialchars($services); ?></td>
                                                                        <td class="doctor"><?= htmlspecialchars($doctorName); ?></td>
                                                                        <td class="medical">
                                                                            <span class="badge <?= $medicalStatusClass; ?>"><?= htmlspecialchars($row['medical']); ?></span>
                                                                        </td>
                                                                        <td class="payment_status">
                                                                            <span class="badge <?= $paymentStatusClass; ?>"><?= htmlspecialchars($row['paid']); ?></span>
                                                                        </td>
                                                                        <td class="service_status">
                                                                            <span class="badge <?= $serviceStatusClass; ?>"><?= htmlspecialchars($row['status']); ?></span>
                                                                        </td>


                                                                        <td>
                                                                            <a href="#" class="btn btn-light btn-sm edit-btn"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#editAppointment"
                                                                                data-app-id="<?php echo $row['app_id']; ?>"
                                                                                data-service-cost="<?php echo $row['total_service_cost']; ?>"> <!-- Corrected data-service-cost -->
                                                                                <i class="ri-edit-fill align-bottom me-2 text-muted"></i> Update
                                                                            </a>

                                                                            <a href="#" class="btn btn-danger btn-sm archive-btn"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#archiveAppointment"
                                                                                data-appointment-id="<?= htmlspecialchars($row['app_id']); ?>">
                                                                                <i class="ri-delete-bin-fill align-bottom me-2"></i> Archive
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                <?php
                                                                }
                                                            } else {
                                                                ?>
                                                                <tr>
                                                                    <td colspan="8">
                                                                        <div class="noresult text-center">
                                                                            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json"
                                                                                trigger="loop"
                                                                                colors="primary:#121331,secondary:#08a88a"
                                                                                style="width:75px;height:75px">
                                                                            </lord-icon>
                                                                            <h5 class="mt-2">Sorry! No Result Found</h5>
                                                                            <p class="text-muted mb-0">We've searched in our database but did not find any data yet!</p>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="d-flex justify-content-end">
                                                    <div class="pagination-wrap hstack gap-2" style="display: flex;">
                                                        <a class="page-item pagination-prev disabled" href="javascript:void(0);">
                                                            Previous
                                                        </a>
                                                        <ul class="pagination listjs-pagination mb-0">
                                                            <li clas="active"><a class="btn btn-primary btn-sm" href="#">1</a></li>
                                                            <li clas=""><a class="btn btn-sm" style="border: 1px solid #e9ebec;" href="#">2</a></li>
                                                            <li clas=""><a class="btn btn-sm" style="border: 1px solid #e9ebec;" href="#">3</a></li>
                                                        </ul>
                                                        <a class="page-item pagination-next" href="javascript:void(0);">
                                                            Next
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end card-body -->
                            </div>
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

    <?php include "modals/edit_appointment.php"; ?>

    <!-- ARCHIVE APPOINTMENT -->
    <div id="archiveAppointment" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Archive Appointment Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="appointmentIdDelete" name="appointmentIdDelete">
                    <p>Are you sure you want to archive this appointment?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="archive_appointment" class="btn btn-danger">Archive</button>
                </div>
            </form>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Modern colorpicker bundle -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="assets/js/pages/listjs.init.js"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>

    <script src="assets/js/sweetalert.js"></script>
    <script>
        flatpickr("#datePicker");
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>

    <script type="text/javascript">
        function printMedicalRecord(appointmentId) {
            var printWindow = window.open("../assets/docs/hematology.php?appointmentId=" + appointmentId, "_blank", "width=800,height=600");
            printWindow.onload = function() {
                printWindow.print();
            };
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Functionality for the Edit button
            const editButtons = document.querySelectorAll('.edit-btn');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const appId = this.getAttribute('data-app-id'); // Get the appointment ID

                    fetch(`get_appointment_data.php?app_id=${appId}`)
                        .then(response => response.json())
                        .then(data => {
                            // Clear the services table
                            document.getElementById('servicesList').innerHTML = '';
                            let totalCost = 0; // Initialize total cost

                            // Populate main appointment details
                            const appointment = data[0];
                            document.getElementById('id').value = appointment.id;
                            document.getElementById('appointmentId').value = appointment.app_id;
                            document.getElementById('patientName').value = `${appointment.patient_firstname} ${appointment.patient_lastname}`;
                            document.getElementById('appointmentDate').value = appointment.appointment_date;
                            document.getElementById('medical').value = appointment.medical;
                            document.getElementById('status').value = appointment.status;
                            document.getElementById('paid').value = appointment.paid;

                            // Populate services table and calculate total cost
                            data.forEach(service => {
                                const row = document.createElement('tr');
                                row.setAttribute('data-service-id', service.id);

                                row.innerHTML = `
                                <td>${service.id}</td>
                                <td>${service.service_name}</td>
                                <td>${service.service_cost}</td>
                                <td>Dr. ${service.doctor_firstname} ${service.doctor_lastname}</td>
                                <td>
                                    <select name="medical" id="newmedical-${service.id}" class="form-control">
                                        <option value="Pending" ${service.medical === 'Pending' ? 'selected' : ''}>Pending</option>
                                        <option value="Completed" ${service.medical === 'Completed' ? 'selected' : ''}>Completed</option>
                                        <option value="Cancelled" ${service.medical === 'Cancelled' ? 'selected' : ''}>Cancelled</option>
                                        <option value="" ${service.medical === '' ? 'selected' : ''}></option>
                                    </select>
                                </td>
                                <td>
                                    <select name="paid" id="newpaid-${service.id}" class="form-control">
                                        <option value="Pending" ${service.paid === 'Pending' ? 'selected' : ''}>Pending</option>
                                        <option value="Completed" ${service.paid === 'Completed' ? 'selected' : ''}>Completed</option>
                                        <option value="Cancelled" ${service.paid === 'Cancelled' ? 'selected' : ''}>Cancelled</option>
                                        <option value="" ${service.paid === '' ? 'selected' : ''}></option>
                                    </select>
                                </td>
                                <td>
                                    <select name="status" id="newstatus-${service.id}" class="form-control">
                                        <option value="Pending" ${service.status === 'Pending' ? 'selected' : ''}>Pending</option>
                                        <option value="Completed" ${service.status === 'Completed' ? 'selected' : ''}>Completed</option>
                                        <option value="Cancelled" ${service.status === 'Cancelled' ? 'selected' : ''}>Cancelled</option>
                                        <option value="" ${service.status === '' ? 'selected' : ''}></option>
                                    </select>
                                </td>
                                
                            `;

                                document.getElementById('servicesList').appendChild(row);

                                // Include in total cost calculation only if status and paid are valid
                                if (service.status !== '' && service.paid !== '' && service.status !== 'Cancelled' && service.paid !== 'Cancelled') {
                                    totalCost += parseFloat(service.service_cost);
                                }
                            });

                            // Update total cost field
                            document.getElementById('totalCost').value = totalCost.toFixed(2);
                        })
                        .catch(error => console.error('Error fetching appointment data:', error));
                });
            });

            // Save changes functionality
            document.getElementById('saveChangesBtn').addEventListener('click', function() {
                const appointmentId = document.getElementById('appointmentId').value;
                const patientName = document.getElementById('patientName').value;
                const appointmentDate = document.getElementById('appointmentDate').value;
                let totalCost = 0; // Recalculate total cost dynamically
                const services = []; // Collect updated services information

                // Iterate over each row in the services list
                document.querySelectorAll('#servicesList tr').forEach(row => {
                    const serviceId = row.getAttribute('data-service-id');
                    const medical = document.getElementById(`newmedical-${serviceId}`).value;
                    const status = document.getElementById(`newstatus-${serviceId}`).value;
                    const paid = document.getElementById(`newpaid-${serviceId}`).value;
                    const cost = parseFloat(row.querySelector('td:nth-child(3)').textContent); // Get service cost

                    // Push the updated service data to the array
                    services.push({
                        serviceId: serviceId,
                        status: status,
                        medical: medical,
                        paid: paid
                    });

                    // Include in total cost calculation only if status and paid are valid
                    if (status !== '' && paid !== '' && status !== 'Cancelled' && paid !== 'Cancelled') {
                        totalCost += cost;
                    }
                });

                // Update total cost field
                document.getElementById('totalCost').value = totalCost.toFixed(2);

                // Prepare the data to be sent
                const updatedData = {
                    appointmentId: appointmentId,
                    patientName: patientName,
                    appointmentDate: appointmentDate,
                    totalCost: totalCost.toFixed(2), // Include recalculated total cost
                    services: services
                };

                // Send update request to the server
                fetch('update_appointment.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(updatedData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message); // Display success message
                        } else {
                            alert(`Error: ${data.message}`); // Display error message
                        }
                    })
                    .catch(error => console.error('Request failed:', error));
            });
        });
    </script>


    <script>
        // Get references to the dropdowns
        const paymentStatus = document.getElementById('paymentStatus');
        const appointmentStatus = document.getElementById('appointmentStatus');

        // Add event listener to payment status dropdown
        paymentStatus.addEventListener('change', () => {
            // Disable "Completed" option regardless of payment status
            for (let option of appointmentStatus.options) {
                if (option.value === 'Completed') {
                    option.disabled = true; // Always disable "Completed" option
                    appointmentStatus.value = 'Pending'; // Reset status if already selected
                }
            }
        });
        // Trigger change event to apply initial restrictions on page load
        paymentStatus.dispatchEvent(new Event('change'));
    </script>

    <script>
        flatpickr("#datePicker");
    </script>
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

    <script>
        // JavaScript to handle populating data in the edit modal
        var editButtons = document.querySelectorAll('.archive-btn');
        editButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                // Get the product details from data attributes
                var appointmentId = this.getAttribute('data-appointment-id');

                // Set the product details in the modal form
                document.getElementById('appointmentIdDelete').value = appointmentId;
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#addAppointmentModal').modal();
        });
    </script>
    <script>
        function searchTable() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("customerTable");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td");
                if (td.length > 0) {
                    var showRow = false;
                    for (var j = 0; j < td.length; j++) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            showRow = true;
                            break; // Stop looking at other columns for this row
                        }
                    }
                    if (showRow) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>


</body>

</html>