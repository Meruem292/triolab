<?php

require "db.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
};

$doctor_id = $_SESSION['user_id'];

if (isset($_POST['edit_appointment'])) {
    $doctorId = $_POST['doctor_id'];
    $appointmentId = $_POST['appointment_id'];
    $appointmentStatus = $_POST['appointment_status'];

    $updateQuery = $pdo->query("UPDATE appointment SET doctor_id = '$doctorId', status = '$appointmentStatus' WHERE id = '$appointmentId'");

    if ($updateQuery) {
        $_SESSION['message'] = "Appointment updated successfully.";
        $_SESSION['status'] = "success";
    } else {
        $_SESSION['message'] = "Error updating appointment.";
        $_SESSION['status'] = "error";
    }
};

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
    <link rel="stylesheet" href="assets/css/sweetalert.css">
    <script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
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
                                    <button class="btn btn-primary">+ New Appointment</button>
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs nav-tabs-custom nav-success nav-justified mb-3" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#new" role="tab">
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
                                        <div class="tab-pane active" id="new" role="tabpanel">
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
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="list">
                                                            <?php
                                                            $selectAppointment = $pdo->query("SELECT *, appointment.id AS appointment_id, patient.id AS patient_id, services.id AS service_id, patient.firstname AS patient_firstname, patient.lastname AS patient_lastname, doctor.firstname AS doctor_firstname, doctor.lastname AS doctor_lastname FROM appointment INNER JOIN patient ON appointment.patient_id = patient.id INNER JOIN services ON appointment.service_id = services.id LEFT JOIN doctor ON appointment.doctor_id = doctor.employee_id WHERE appointment.is_archive = 0 AND appointment.status = 'Pending' ORDER BY appointment.date_added ASC");
                                                            if ($selectAppointment->rowCount() > 0) {
                                                                while ($row = $selectAppointment->fetch(PDO::FETCH_ASSOC)) {
                                                                    $time = $row['appointment_time'];
                                                                    $formatted_time = date("g:i A", strtotime($time));

                                                                    $date = $row['appointment_date'];
                                                                    $formatted_date = date("F j, Y", strtotime($date));

                                                                    $fullnamePatient = $row['patient_firstname'] . " " . $row['patient_lastname'];
                                                                    $fullnameDoctor = $row['doctor_id'] == NULL ? "N/A" : "Dr. " . $row['doctor_firstname'] . " " . $row['doctor_lastname'];

                                                                    $serviceName = $row['service'] . " (" . $row['type'] . ")";
                                                                    $cost = "₱" . number_format($row['cost'], 2);

                                                            ?>
                                                                    <tr>
                                                                        <td class="time"><?= $formatted_time; ?></td>
                                                                        <td class="date"><?= $formatted_date; ?></td>
                                                                        <td class="patient_name"><?= $fullnamePatient; ?></td>
                                                                        <td class="service"><?= $serviceName; ?></td>
                                                                        <td class="doctor"><?= $fullnameDoctor; ?></td>
                                                                        <td>
                                                                            <div class="dropdown d-inline-block">
                                                                                <button class="btn btn-soft-success btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                    <i class="ri-more-fill align-middle"></i>
                                                                                </button>
                                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                                    <li>
                                                                                        <a href="#" class="dropdown-item remove-item-btn edit-btn" data-bs-toggle="modal" data-bs-target="#editAppointment" data-appointment-id="<?= $row['appointment_id'] ?>" data-appointment-patient="<?= $fullnamePatient ?>" data-appointment-doctor="<?= $fullnameDoctor ?>" data-appointment-service="<?= $serviceName ?>" data-appointment-cost="<?= $cost ?>" data-appointment-payment="<?= $row['selectedPayment'] ?>" data-appointment-date="<?= $formatted_date ?>" data-appointment-time="<?= $formatted_time ?>" data-appointment-status="<?= $row['status'] ?>">
                                                                                            <i class="ri-edit-fill align-bottom me-2 text-muted"></i> Update
                                                                                        </a>
                                                                                    </li>
                                                                                    <li>
                                                                                        <a href="#" class="dropdown-item remove-item-btn archive-btn" data-bs-toggle="modal" data-bs-target="#archiveAppointment" data-appointment-id="<?= $row['appointment_id'] ?>">
                                                                                            <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Archive
                                                                                        </a>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
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
                                                                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                                                                                <h5 class="mt-2">Sorry! No Result Found</h5>
                                                                                <p class="text-muted mb-0">We've searched in our database but we did not find any data yet!</p>
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
                                                                <th>Patient Age</th>
                                                                <th>Doctor</th>
                                                                <th>Fee Status</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="list">
                                                            <?php
                                                            $selectAppointment = $pdo->query("SELECT *, appointment.id AS appointment_id, patient.id AS patient_id, services.id AS service_id, patient.firstname AS patient_firstname, patient.lastname AS patient_lastname, doctor.firstname AS doctor_firstname, doctor.lastname AS doctor_lastname FROM appointment INNER JOIN patient ON appointment.patient_id = patient.id INNER JOIN services ON appointment.service_id = services.id LEFT JOIN doctor ON appointment.doctor_id = doctor.employee_id WHERE appointment.is_archive = 0 AND appointment.status = 'Approved' ORDER BY appointment.date_added ASC");
                                                            if ($selectAppointment->rowCount() > 0) {
                                                                while ($row = $selectAppointment->fetch(PDO::FETCH_ASSOC)) {
                                                                    $time = $row['appointment_time'];
                                                                    $formatted_time = date("g:i A", strtotime($time));

                                                                    $date = $row['appointment_date'];
                                                                    $formatted_date = date("F j, Y", strtotime($date));

                                                                    $fullnamePatient = $row['patient_firstname'] . " " . $row['patient_lastname'];
                                                                    $fullnameDoctor = $row['doctor_id'] == NULL ? "N/A" : "Dr. " . $row['doctor_firstname'] . " " . $row['doctor_lastname'];

                                                                    $serviceName = $row['service'] . " (" . $row['type'] . ")";
                                                                    $cost = "₱" . number_format($row['cost'], 2);

                                                                    date_default_timezone_set('Asia/Manila');
                                                                    $birthdate = $row['dob'];

                                                                    // Calculate age
                                                                    $today = new DateTime("now");
                                                                    $birthday = new DateTime($birthdate);
                                                                    $age = $today->diff($birthday)->y;

                                                            ?>
                                                                    <tr>
                                                                        <td class="time"><?= $formatted_time; ?></td>
                                                                        <td class="date"><?= $formatted_date; ?></td>
                                                                        <td class="patient_name"><?= $fullnamePatient; ?></td>
                                                                        <td class="patient_name"><?= $age; ?></td>
                                                                        <td class="doctor"><?= $fullnameDoctor; ?></td>
                                                                        <td class="service"><span class="badge <?= $row['paid'] == 'Paid' ? 'bg-primary' : 'bg-danger' ?>"><?= $row['paid'] ?></span></td>
                                                                        <td>
                                                                            <button class="btn btn-soft-success btn-sm">
                                                                                Request Fee
                                                                            </button>
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
                                                                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                                                                                <h5 class="mt-2">Sorry! No Result Found</h5>
                                                                                <p class="text-muted mb-0">We've searched in our database but we did not find any data yet!</p>
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

    <!-- UPDATE APPOINTMENT -->
    <div id="editAppointment" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Appointment Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 mb-3">
                            <input type="hidden" name="appointment_id" id="appointmentId">
                            <input type="hidden" name="doctor_id" value="<?= $doctor_id; ?>">
                        </div>
                        <div class="col-md-12 col-sm-12 mb-3">
                            <p><b>Patient Name: </b> <span id="appointmentPatient"></span></p>
                        </div>
                        <div class="col-md-12 col-sm-12 mb-3">
                            <p><b>Doctor Name: </b> <span id="appointmentDoctor"></span></p>
                        </div>
                        <div class="col-md-12 col-sm-12 mb-3">
                            <p><b>Service: </b> <span id="appointmentService"></span></p>
                        </div>
                        <div class="col-md-12 col-sm-12 mb-3">
                            <p><b>Cost: </b> <span id="appointmentCost"></span></p>
                        </div>
                        <div class="col-md-12 col-sm-12 mb-3">
                            <p><b>Payment Method: </b> <span id="appointmentPayment"></span></p>
                        </div>
                        <div class="col-md-12 col-sm-12 mb-3">
                            <p><b>Appointment Date: </b> <span id="appointmentDate"></span></p>
                        </div>
                        <div class="col-md-12 col-sm-12 mb-3">
                            <p><b>Appointment Time: </b> <span id="appointmentTime"></span></p>
                        </div>
                        <div class="col-md-12 col-sm-12 mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="appointment_status" id="appointmentStatus" class="form-select" required>
                                <option value="Pending" selected>Pending</option>
                                <option value="Approved">Approve</option>
                                <option value="Declined">Decline</option>
                                <option value="Completed">Mark as Complete</option>
                            </select>
                        </div>
                    </div>
                </div>
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
    <script src="assets/js/sweetalert.js"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>

    <script>
        flatpickr("#datePicker");
    </script>

    <script>
        // JavaScript to handle populating data in the edit modal
        var editButtons = document.querySelectorAll('.edit-btn');
        editButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var appointmentId = this.getAttribute('data-appointment-id');
                var appointmentPatient = this.getAttribute('data-appointment-patient');
                var appointmentDoctor = this.getAttribute('data-appointment-doctor');
                var appointmentService = this.getAttribute('data-appointment-service');
                var appointmentCost = this.getAttribute('data-appointment-cost');
                var appointmentPayment = this.getAttribute('data-appointment-payment');
                var appointmentDate = this.getAttribute('data-appointment-date');
                var appointmentTime = this.getAttribute('data-appointment-time');
                var appointmentStatus = this.getAttribute('data-appointment-status');

                document.getElementById('appointmentId').value = appointmentId;
                document.getElementById('appointmentPatient').textContent = appointmentPatient.toUpperCase();
                document.getElementById('appointmentDoctor').textContent = appointmentDoctor.toUpperCase();
                document.getElementById('appointmentService').textContent = appointmentService.toUpperCase();
                document.getElementById('appointmentCost').textContent = appointmentCost.toUpperCase();
                document.getElementById('appointmentPayment').textContent = appointmentPayment.toUpperCase();
                document.getElementById('appointmentDate').textContent = appointmentDate.toUpperCase();
                document.getElementById('appointmentTime').textContent = appointmentTime.toUpperCase();
                document.getElementById('appointmentStatus').value = appointmentStatus;
            });
        });
    </script>

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