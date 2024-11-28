<?php

require "db.php";
session_start();

$admin_id = $_SESSION['user_id'];
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
};

if (isset($_POST['edit_appointment'])) {
    // Extract form inputs
    $appointmentId = $_POST['appointmentId'];  // Appointment ID
    $paymentStatus = $_POST['paymentStatus'];  // Payment Status (Pending, Approved, Disapproved)
    $status = $_POST['appointmentStatus'];     // Appointment Status (Pending, Completed)
    $doctor = $_POST['doctor'];                // Doctor ID
    $amount = $_POST['amount'];                // Payment amount

    try {
        // Begin transaction
        $pdo->beginTransaction();

        // Get the department ID of the selected doctor
        $queryDoctorDepartment = $pdo->prepare("SELECT department_id FROM doctor WHERE employee_id = ?");
        $queryDoctorDepartment->execute([$doctor]);
        $doctorDepartment = $queryDoctorDepartment->fetch(PDO::FETCH_ASSOC);

        if (!$doctorDepartment) {
            throw new Exception("Invalid doctor selected.");
        }

        $doctorDepartmentId = $doctorDepartment['department_id'];

        // Update `appointment` table
        $updateAppointment = $pdo->prepare(
            "UPDATE appointment 
             SET status = ?, paid = ?, doctor_id = ?, department_id = ? 
             WHERE id = ?"
        );
        $updateAppointment->execute([$status, $paymentStatus, $doctor, $doctorDepartmentId, $appointmentId]);

        // Check if a payment receipt already exists for the appointment
        $checkReceipt = $pdo->prepare("SELECT id FROM payment_receipts WHERE appointment_id = ?");
        $checkReceipt->execute([$appointmentId]);
        $existingReceipt = $checkReceipt->fetch(PDO::FETCH_ASSOC);

        if ($existingReceipt) {
            // Update the amount in `payment_receipts` if it exists
            $updateReceipt = $pdo->prepare("UPDATE payment_receipts SET amount = ?, status = ? WHERE appointment_id = ?");
            $updateReceipt->execute([$amount, $paymentStatus, $appointmentId]);
        } else {
            // Insert a new payment receipt if it doesn't exist
            $insertReceipt = $pdo->prepare(
                "INSERT INTO payment_receipts (appointment_id, payment_receipt_path, date, payment_mode_id, amount, status) 
                 VALUES (?, ?, NOW(), 3, ?, ?)"
            );
            $insertReceipt->execute([$appointmentId, '', $amount, 'Pending']); // Default values for missing fields
        }

        // Check if a medical record already exists for the appointment
        $checkMedicalRecord = $pdo->prepare("SELECT id FROM medical_records WHERE appointment_id = ?");
        $checkMedicalRecord->execute([$appointmentId]);
        $existingMedicalRecord = $checkMedicalRecord->fetch(PDO::FETCH_ASSOC);

        if ($existingMedicalRecord) {
            // Update the medical record if it exists
            $updateMedicalRecord = $pdo->prepare(
                "UPDATE medical_records 
                 SET doctor_id = ?, updated_at = NOW() 
                 WHERE appointment_id = ?"
            );
            $updateMedicalRecord->execute([$doctor, $appointmentId]);
        } else {
            // Insert a new medical record if it doesn't exist
            $insertMedicalRecord = $pdo->prepare(
                "INSERT INTO medical_records (patient_id, appointment_id, doctor_id, status, record_date, created_at) 
                 SELECT patient_id, id, ?, 'Pending', NOW(), NOW() 
                 FROM appointment 
                 WHERE id = ?"
            );
            $insertMedicalRecord->execute([$doctor, $appointmentId]);
        }

        // Commit transaction
        $pdo->commit();

        // Set success message
        $_SESSION['message'] = "Appointment, payment, and medical record details updated successfully!";
        $_SESSION['status'] = "success";
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $pdo->rollBack();

        // Set error message
        $_SESSION['message'] = "Error updating appointment details: " . $e->getMessage();
        $_SESSION['status'] = "error";
    }

    // Redirect to the appointment page
    header('Location: ../admin/appointment.php');
    exit();
}




if (isset($_POST['archive_appointment'])) {
    $appointmentIdDelete = $_POST['appointmentIdDelete'];
    $archive = '1';

    // Update the service information in the database
    $archiveQuery = $pdo->prepare("UPDATE appointment SET is_archive = :archive WHERE id = :id");
    $archiveQuery->bindParam(':archive', $archive);
    $archiveQuery->bindParam(':id', $appointmentIdDelete);

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
                                                                <th>Payment Status</th> <!-- New Column -->
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="list">
                                                            <?php
                                                            $selectAppointment = $pdo->query("
           SELECT 
    appointment.id AS appointment_id, 
    patient.firstname AS patient_firstname, 
    patient.lastname AS patient_lastname, 
    doctor.firstname AS doctor_firstname, 
    doctor.lastname AS doctor_lastname, 
    services.service, 
    services.type, 
    services.cost, 
    appointment.appointment_time, 
    appointment.appointment_date, 
    appointment.doctor_id, 
    appointment.selectedPayment, 
    appointment.status, 
    appointment.paid,  -- Include the payment status
    payment_receipts.amount AS payment_amount,  -- Amount from payment_receipts
    payment_receipts.status AS payment_status,  -- Payment status
    payment_receipts.date AS payment_date,  -- Payment receipt date
    medical_records.diagnosis,  -- Include the diagnosis from medical_records
    medical_records.treatment,  -- Include the treatment from medical_records
    medical_records.prescription,  -- Include the prescription from medical_records
    medical_records.status AS medical_status,  -- Include the status from medical_records
    medical_records.record_date  -- Include the record date from medical_records
FROM appointment 
INNER JOIN patient ON appointment.patient_id = patient.id 
INNER JOIN services ON appointment.service_id = services.id 
LEFT JOIN doctor ON appointment.doctor_id = doctor.employee_id 
LEFT JOIN payment_receipts ON appointment.id = payment_receipts.appointment_id  -- Join payment_receipts
LEFT JOIN medical_records ON appointment.id = medical_records.appointment_id  -- Join medical_records
WHERE appointment.is_archive = 0 
    AND appointment.status = 'Pending' 
ORDER BY appointment.date_added ASC;
        ");

                                                            if ($selectAppointment->rowCount() > 0) {
                                                                while ($row = $selectAppointment->fetch(PDO::FETCH_ASSOC)) {
                                                                    // Format time and date
                                                                    $formatted_time = date("g:i A", strtotime($row['appointment_time']));
                                                                    $formatted_date = date("F j, Y", strtotime($row['appointment_date']));

                                                                    // Combine names
                                                                    $fullnamePatient = $row['patient_firstname'] . " " . $row['patient_lastname'];
                                                                    $fullnameDoctor = $row['doctor_id']
                                                                        ? "Dr. " . $row['doctor_firstname'] . " " . $row['doctor_lastname']
                                                                        : "N/A";

                                                                    // Service details
                                                                    $serviceName = $row['service'] . " (" . $row['type'] . ")";

                                                                    $medicalStatus = $row['medical_status']; // Reflect the actual medical status
                                                                    $medicalStatusClass = '';

                                                                    switch ($medicalStatus) {
                                                                        case 'Pending':
                                                                            $medicalStatusClass = 'bg-warning text-dark'; // Yellow background with dark text for Pending
                                                                            break;
                                                                        case 'Completed':
                                                                            $medicalStatusClass = 'bg-success text-white'; // Green background with white text for Approved
                                                                            break;
                                                                        default:
                                                                            $medicalStatusClass = 'bg-secondary text-white'; // Default grey background for other statuses
                                                                    }

                                                                    // Determine the Bootstrap class based on the payment status
                                                                    $paymentStatus = $row['paid']; // Reflect the actual payment status
                                                                    $statusClass = '';

                                                                    switch ($paymentStatus) {
                                                                        case 'Pending':
                                                                            $statusClass = 'bg-warning text-dark'; // Yellow background with dark text for Pending
                                                                            break;
                                                                        case 'Approved':
                                                                            $statusClass = 'bg-success text-white'; // Green background with white text for Approved
                                                                            break;
                                                                        case 'Disapproved':
                                                                            $statusClass = 'bg-danger text-white'; // Red background with white text for Disapproved
                                                                            break;
                                                                        default:
                                                                            $statusClass = 'bg-secondary text-white'; // Default grey background for other statuses
                                                                    }



                                                            ?>
                                                                    <tr>
                                                                        <td class="time"><?= htmlspecialchars($formatted_time); ?></td>
                                                                        <td class="date"><?= htmlspecialchars($formatted_date); ?></td>
                                                                        <td class="patient_name"><?= htmlspecialchars($fullnamePatient); ?></td>
                                                                        <td class="service"><?= htmlspecialchars($serviceName); ?></td>
                                                                        <td class="doctor"><?= htmlspecialchars($fullnameDoctor); ?></td>
                                                                        <td class="service_status">
                                                                            <span class="badge <?= $medicalStatusClass; ?>"><?= htmlspecialchars($medicalStatus); ?></span>
                                                                        </td> <!-- New Column -->
                                                                        <td class="payment_status">
                                                                            <span class="badge <?= $statusClass; ?>"><?= htmlspecialchars($paymentStatus); ?></span>
                                                                        </td> <!-- New Column -->
                                                                        <td>
                                                                            <a href="#" class="btn btn-light btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#editAppointment"
                                                                                data-appointment-id="<?= htmlspecialchars($row['appointment_id']); ?>"
                                                                                data-patient-name="<?= htmlspecialchars($fullnamePatient); ?>"
                                                                                data-doctor-name="<?= htmlspecialchars($fullnameDoctor); ?>"
                                                                                data-service="<?= htmlspecialchars($row['service']); ?>"
                                                                                data-cost="<?= htmlspecialchars($row['cost']); ?>"
                                                                                data-payment-amount="<?= htmlspecialchars($row['payment_amount']); ?>"
                                                                                data-payment-method="<?= htmlspecialchars(getPaymentMode($pdo, $row['selectedPayment'])); ?>"
                                                                                data-appointment-date="<?= htmlspecialchars($row['appointment_date']); ?>"
                                                                                data-appointment-time="<?= htmlspecialchars($row['appointment_time']); ?>"
                                                                                data-payment-status="<?= htmlspecialchars($paymentStatus); ?>"
                                                                                data-status="<?= htmlspecialchars($row['status']); ?>">
                                                                                <i class="ri-edit-fill align-bottom me-2 text-muted"></i> Update
                                                                            </a>
                                                                            <a href="#" class="btn btn-danger btn-sm archive-btn" data-bs-toggle="modal" data-bs-target="#archiveAppointment"
                                                                                data-appointment-id="<?= htmlspecialchars($row['appointment_id']); ?>">
                                                                                <i class="ri-delete-bin-fill align-bottom me-2"></i> Archive
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                <?php
                                                                }
                                                            } else {
                                                                ?>
                                                                <tr>
                                                                    <td colspan="6">
                                                                        <div class="noresult">
                                                                            <div class="text-center">
                                                                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json"
                                                                                    trigger="loop"
                                                                                    colors="primary:#121331,secondary:#08a88a"
                                                                                    style="width:75px;height:75px">
                                                                                </lord-icon>
                                                                                <h5 class="mt-2">Sorry! No Result Found</h5>
                                                                                <p class="text-muted mb-0">We've searched in our database but did not find any data yet!</p>
                                                                            </div>
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
                                                    <div class="search-box ms-2">
                                                        <input type="text" class="form-control search" placeholder="Search...">
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
                                                                <th>Payment Status</th> <!-- New Column -->
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="list">
                                                            <?php
                                                            $selectAppointment = $pdo->query("
            SELECT 
    appointment.id AS appointment_id, 
    patient.firstname AS patient_firstname, 
    patient.lastname AS patient_lastname, 
    doctor.firstname AS doctor_firstname, 
    doctor.lastname AS doctor_lastname, 
    services.service, 
    services.type, 
    services.cost, 
    appointment.appointment_time, 
    appointment.appointment_date, 
    appointment.doctor_id, 
    appointment.selectedPayment, 
    appointment.status, 
    appointment.paid,  -- Include the payment status
    payment_receipts.amount AS payment_amount,  -- Amount from payment_receipts
    payment_receipts.status AS payment_status,  -- Payment status
    payment_receipts.date AS payment_date  -- Payment receipt date
FROM appointment 
INNER JOIN patient ON appointment.patient_id = patient.id 
INNER JOIN services ON appointment.service_id = services.id 
LEFT JOIN doctor ON appointment.doctor_id = doctor.employee_id 
LEFT JOIN payment_receipts ON appointment.id = payment_receipts.appointment_id  -- Join payment_receipts
WHERE appointment.is_archive = 0

    AND appointment.status = 'Completed' 
    AND appointment.paid = 'Approved'
ORDER BY appointment.date_added ASC;

        ");

                                                            if ($selectAppointment->rowCount() > 0) {
                                                                while ($row = $selectAppointment->fetch(PDO::FETCH_ASSOC)) {
                                                                    // Format time and date
                                                                    $formatted_time = date("g:i A", strtotime($row['appointment_time']));
                                                                    $formatted_date = date("F j, Y", strtotime($row['appointment_date']));

                                                                    // Combine names
                                                                    $fullnamePatient = $row['patient_firstname'] . " " . $row['patient_lastname'];
                                                                    $fullnameDoctor = $row['doctor_id']
                                                                        ? "Dr. " . $row['doctor_firstname'] . " " . $row['doctor_lastname']
                                                                        : "N/A";

                                                                    // Service details
                                                                    $serviceName = $row['service'] . " (" . $row['type'] . ")";

                                                                    // Determine the Bootstrap class based on the payment status
                                                                    $paymentStatus = $row['paid']; // Reflect the actual payment status
                                                                    $statusClass = '';

                                                                    switch ($paymentStatus) {
                                                                        case 'Pending':
                                                                            $statusClass = 'bg-warning text-dark'; // Yellow background with dark text for Pending
                                                                            break;
                                                                        case 'Approved':
                                                                            $statusClass = 'bg-success text-white'; // Green background with white text for Approved
                                                                            break;
                                                                        case 'Disapproved':
                                                                            $statusClass = 'bg-danger text-white'; // Red background with white text for Disapproved
                                                                            break;
                                                                        default:
                                                                            $statusClass = 'bg-secondary text-white'; // Default grey background for other statuses
                                                                    }

                                                            ?>
                                                                    <tr>
                                                                        <td class="time"><?= htmlspecialchars($formatted_time); ?></td>
                                                                        <td class="date"><?= htmlspecialchars($formatted_date); ?></td>
                                                                        <td class="patient_name"><?= htmlspecialchars($fullnamePatient); ?></td>
                                                                        <td class="service"><?= htmlspecialchars($serviceName); ?></td>
                                                                        <td class="doctor"><?= htmlspecialchars($fullnameDoctor); ?></td>
                                                                        <td class="payment_status">
                                                                            <span class="badge <?= $statusClass; ?>"><?= htmlspecialchars($paymentStatus); ?></span>
                                                                        </td> <!-- New Column -->
                                                                        <td>
                                                                            <a href="#" class="btn btn-light btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#editAppointment"
                                                                                data-appointment-id="<?= htmlspecialchars($row['appointment_id']); ?>"
                                                                                data-patient-name="<?= htmlspecialchars($fullnamePatient); ?>"
                                                                                data-doctor-name="<?= htmlspecialchars($fullnameDoctor); ?>"
                                                                                data-service="<?= htmlspecialchars($row['service']); ?>"
                                                                                data-cost="<?= htmlspecialchars($row['cost']); ?>"
                                                                                data-payment-amount="<?= htmlspecialchars($row['payment_amount']); ?>"
                                                                                data-payment-method="<?= htmlspecialchars(getPaymentMode($pdo, $row['selectedPayment'])); ?>"
                                                                                data-appointment-date="<?= htmlspecialchars($row['appointment_date']); ?>"
                                                                                data-appointment-time="<?= htmlspecialchars($row['appointment_time']); ?>"
                                                                                data-payment-status="<?= htmlspecialchars($paymentStatus); ?>"
                                                                                data-status="<?= htmlspecialchars($row['status']); ?>">
                                                                                <i class="ri-edit-fill align-bottom me-2 text-muted"></i> Update
                                                                            </a>
                                                                            <a href="#" class="btn btn-danger btn-sm archive-btn" data-bs-toggle="modal" data-bs-target="#archiveAppointment"
                                                                                data-appointment-id="<?= htmlspecialchars($row['appointment_id']); ?>">
                                                                                <i class="ri-delete-bin-fill align-bottom me-2"></i> Archive
                                                                            </a>
                                                                            <a href="../doctor/docs/printing_layout.php?appointmentId=<?= $row['appointment_id']; ?>" class="btn btn-info btn-sm">
                                                                                <i class="ri-printer-fill align-bottom me-2"></i> Print Medical Record
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                <?php
                                                                }
                                                            } else {
                                                                ?>
                                                                <tr>
                                                                    <td colspan="6">
                                                                        <div class="noresult">
                                                                            <div class="text-center">
                                                                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json"
                                                                                    trigger="loop"
                                                                                    colors="primary:#121331,secondary:#08a88a"
                                                                                    style="width:75px;height:75px">
                                                                                </lord-icon>
                                                                                <h5 class="mt-2">Sorry! No Result Found</h5>
                                                                                <p class="text-muted mb-0">We've searched in our database but did not find any data yet!</p>
                                                                            </div>
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

    <div id="editAppointment" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">Update Appointment Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="row">
                        <!-- Hidden Appointment ID -->
                        <div class="col-md-12 mb-2">
                            <input type="hidden" name="appointmentId" id="appointmentId">
                        </div>

                        <!-- Patient Name -->
                        <div class="col-md-12 mb-2">
                            <p><b>Patient Name: </b> <span id="appointmentPatient"></span></p>
                        </div>

                        <!-- Assigned Doctor -->
                        <div class="col-md-12 mb-2">
                            <p><b>Assigned Doctor: </b> <span id="appointmentDoctor"></span></p>
                            <?php
                            // Order doctors by their department name
                            $doctors = $pdo->query("
            SELECT doctor.*, departments.name AS department_name 
            FROM doctor
            JOIN departments ON doctor.department_id = departments.id
            ORDER BY departments.name
        ");
                            ?>
                            <label class="form-label">Change Doctor</label>
                            <select name="doctor" id="doctor" class="form-select" required>
                                <?php foreach ($doctors as $doctor) { ?>
                                    <option value="<?= $doctor['employee_id']; ?>">
                                        <?= ucfirst(htmlspecialchars($doctor['firstname']) . ' ' . ucfirst($doctor['lastname']) . ' - ' . $doctor['department_name']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>



                        <!-- Service -->
                        <div class="col-md-12 mb-2">
                            <p><b>Service: </b> <span id="appointmentService"></span></p>
                        </div>

                        <!-- Payment Method -->
                        <div class="col-md-12 mb-2">
                            <p><b>Payment Method: </b> <span id="appointmentPayment"></span></p>
                        </div>

                        <!-- Appointment Date -->
                        <div class="col-md-12 mb-2">
                            <p><b>Appointment Date: </b> <span id="appointmentDate"></span></p>
                        </div>

                        <!-- Appointment Time -->
                        <div class="col-md-12 mb-2">
                            <p><b>Appointment Time: </b> <span id="appointmentTime"></span></p>
                        </div>

                        <!-- Service Cost -->
                        <div class="col-md-12 mb-2">
                            <p><b>Service Cost: </b> <span id="appointmentCost"></span></p>
                            <input type="hidden" id="appointmentPaymentAmount">
                        </div>

                        <!-- Total Bill -->
                        <div class="col-md-12 mb-2">
                            <label class="form-label">Total Bill <span class="text-danger">*</span></label>
                            <input type="float" step="0.01" name="amount" class="form-control" placeholder="Enter total amount" required>
                        </div>

                        <!-- Payment Status -->
                        <div class="col-md-12 mb-2">
                            <label class="form-label">Payment Status <span class="text-danger">*</span></label>
                            <select name="paymentStatus" id="paymentStatus" class="form-select" required>
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Disapproved">Disapproved</option>
                            </select>
                        </div>

                        <!-- Appointment Status -->
                        <div class="col-md-12 mb-2">
                            <label class="form-label">Appointment Status <span class="text-danger">*</span></label>
                            <select name="appointmentStatus" id="appointmentStatus" class="form-select" required>
                                <option value="Pending">Pending</option>
                                <option value="Completed">Mark as Complete</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="edit_appointment" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>


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

    <script type="text/javascript">
        function printMedicalRecord(appointmentId) {
            var printWindow = window.open("../assets/docs/hematology.php?appointmentId=" + appointmentId, "_blank", "width=800,height=600");
            printWindow.onload = function() {
                printWindow.print();
            };
        }
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
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-btn');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const appointmentId = this.getAttribute('data-appointment-id');
                    const patientName = this.getAttribute('data-patient-name');
                    const doctorName = this.getAttribute('data-doctor-name');
                    const service = this.getAttribute('data-service');
                    const cost = this.getAttribute('data-cost');
                    const paymentAmount = this.getAttribute('data-payment-amount');
                    const paymentMethod = this.getAttribute('data-payment-method');
                    const appointmentDate = this.getAttribute('data-appointment-date');
                    const appointmentTime = this.getAttribute('data-appointment-time');
                    const status = this.getAttribute('data-status');
                    const paymentStatus = this.getAttribute('data-payment-status');

                    document.getElementById('appointmentId').value = appointmentId;
                    document.getElementById('appointmentPatient').textContent = patientName;
                    document.getElementById('appointmentDoctor').textContent = doctorName;
                    document.getElementById('appointmentService').textContent = service;
                    document.getElementById('appointmentCost').textContent = cost;
                    document.getElementById('appointmentPaymentAmount').textContent = paymentAmount;
                    document.getElementById('appointmentPayment').textContent = paymentMethod;
                    document.getElementById('appointmentDate').textContent = appointmentDate;
                    document.getElementById('appointmentTime').textContent = appointmentTime;
                    document.getElementById('appointmentStatus').value = status;
                    document.getElementById('paymentStatus').value = paymentStatus;


                    document.querySelector('input[name="amount"]').value = paymentAmount;
                });
            });
        });
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