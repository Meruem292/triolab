<?php
require "db.php";
session_start();
include "logAction.php";

$user_id = $_SESSION['user_id'];
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$serviceId = $_GET['service_id'];

// Fetch service details
$selectService = $pdo->prepare("SELECT * FROM services WHERE id = :serviceId");
$selectService->execute([':serviceId' => $serviceId]);
$fetchService = $selectService->fetch(PDO::FETCH_ASSOC);
if (isset($_POST['add_appointment'])) {
    // Retrieve form data
    $selectedDate = $_POST['selectedDate'];
    $selectedSlot = $_POST['selectedSchedule']; // Appointment slot ID
    $selectedTime = $_POST['selectedTime'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $birthdate = $_POST['birthdate'];
    $birthplace = $_POST['birthplace'];
    $contactNumber = $_POST['contactNumber'];
    $province = $_POST['province'];
    $city = $_POST['city'];
    $barangay = $_POST['barangay'];
    $street = $_POST['street'];
    $selectedPayment = $_POST['selectedPayment'];
    $medical = $_POST['medical'];
    $serviceID = $_POST['serviceID'];
    $receipt = $_FILES['receipt']; // File input for receipt
    $amount = $_POST['amount'];
    $status = "Pending";
    $paid = "Pending";
    $slot = 1;
    $date_added = date('Y-m-d H:i:s');
    $content = null;

    try {
        // Retrieve doctor and department based on the selected service
        $getDoctorAndDepartment = $pdo->prepare("
    SELECT s.department_id AS department_id, d.employee_id AS doctor_id
    FROM services s
    INNER JOIN doctor d ON d.department_id = s.department_id
    WHERE s.id = ? LIMIT 1
");

        $getDoctorAndDepartment->execute([$serviceID]);
        $doctorData = $getDoctorAndDepartment->fetch(PDO::FETCH_ASSOC);

        if ($doctorData) {
            $doctor_id = $doctorData['doctor_id'];
            $department_id = $doctorData['department_id'];
        } else {
            throw new Exception("No doctor found for the selected service.");
        }

        // Insert appointment data
        $insertAppointment = $pdo->prepare(
            "INSERT INTO `appointment` 
            (`service_id`, `patient_id`, `appointment_date`, `appointment_time`, `appointment_slot_id`, `doctor_id`, `department_id`, `selectedPayment`, `medical`, `status`, `paid`, `date_added`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $insertAppointment->execute([$serviceID, $user_id, $selectedDate, $selectedTime, $selectedSlot, $doctor_id, $department_id, $selectedPayment, $medical, $status, $paid, $date_added]);
        $appointment_id = $pdo->lastInsertId();

        // Log the appointment booking action
        logAction($pdo, 'add appointment', 'Appointment booked with ID: ' . $appointment_id . ' for patient ID: ' . $user_id);

        // Handle payment receipt or mark as paid
        if (strtolower($selectedPayment) !== '3') {
            if ($_FILES['receipt']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../triolab/admin/modals/uploads/payment_receipts/';
                $fileName = time() . '_' . basename($_FILES['receipt']['name']);
                $uploadFilePath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['receipt']['tmp_name'], $uploadFilePath)) {
                    $dbReceiptPath = "uploads/payment_receipts/" . $fileName;
                    $insertReceipt = $pdo->prepare(
                        "INSERT INTO `payment_receipts` (`appointment_id`, `payment_receipt_path`, `date`, `payment_mode_id`, `amount`, `status`) 
                        VALUES (?, ?, ?, ?, ?, ?)"
                    );
                    $insertReceipt->execute([$appointment_id, $dbReceiptPath, $date_added, $selectedPayment, $amount, $status]);

                    $updateAppointment = $pdo->prepare("UPDATE `appointment` SET `paid` = 'Pending' WHERE `id` = ?");
                    $updateAppointment->execute([$appointment_id]);

                    $updateAppointmentSlot = $pdo->prepare("UPDATE `appointment_slots` SET `slot` = `slot` - 1 WHERE `id` = ?");
                    $updateAppointmentSlot->execute([$selectedSlot]);

                    $insertMedical = $pdo->prepare("INSERT INTO `medical_records` (`patient_id`, `appointment_id`, `content`) VALUES (?, ?, ?)");
                    $insertMedical->execute([$user_id, $appointment_id, $content]);

                    $_SESSION['message'] = "Appointment booked successfully with payment!";
                    $_SESSION['status'] = "success";
                } else {
                    throw new Exception("Failed to upload the payment receipt.");
                }
            } else {
                throw new Exception("Please upload a valid payment receipt.");
            }
        } else {
            $updateAppointment = $pdo->prepare("UPDATE `appointment` SET `paid` = 'Pending' WHERE `id` = ?");
            $updateAppointment->execute([$appointment_id]);

            $updateAppointmentSlot = $pdo->prepare("UPDATE `appointment_slots` SET `slot` = `slot` - 1 WHERE `id` = ?");
            $updateAppointmentSlot->execute([$selectedSlot]);

            $insertMedical = $pdo->prepare("INSERT INTO `medical_records` (`patient_id`, `appointment_id`, `content`) VALUES (?, ?, ?)");
            $insertMedical->execute([$user_id, $appointment_id, $content]);

            $_SESSION['message'] = "Appointment booked successfully with cash payment!";
            $_SESSION['status'] = "success";
        }
    } catch (Exception $e) {
        $_SESSION['message'] = "An error occurred: " . $e->getMessage();
        $_SESSION['status'] = "error";

        logAction($pdo, 'add appointment error', 'Error booking appointment for patient ID: ' . $user_id . ' - ' . $e->getMessage());
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Triolab - Online Healthcare Management System</title>

    <link rel="shortcut icon" href="assets/images/logo.png" type="image/png">

    <!-- Bootstrap Framework Version 4.5.3 -->
    <link href="assets/css/bootstrap.min.css" type="text/css" rel="stylesheet">

    <!-- Ion Icons Version 5.1.0 -->
    <link href="assets/css/ionicons.css" type="text/css" rel="stylesheet">

    <!-- Medical Icons -->
    <link href="assets/css/medwise-icons.css" type="text/css" rel="stylesheet">

    <!-- Stylesheets -->
    <link href="assets/css/vendors.min.css" type="text/css" rel="stylesheet">
    <link href="assets/css/style.min.css" type="text/css" rel="stylesheet" id="style">
    <link href="assets/css/components.min.css" type="text/css" rel="stylesheet" id="components">
    <link rel="stylesheet" href="assets/css/sweetalert.css">

    <!--Google Fonts-->
    <link rel="preconnect" href="https://fonts.gstatic.com/">
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;0,900;1,400;1,700;1,900&amp;family=Manrope:wght@300;400;600;800&amp;family=Volkhov:ital,wght@0,400;0,700;1,400;1,700&amp;display=swap" rel="stylesheet">

    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

    <style>
        .toggle-block {
            display: block;
            cursor: pointer;
            padding: 10px 20px;
            margin: 20px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .toggle-block.selected {
            background-color: #e8ffec !important;
            color: #008000;
        }

        /* Hide the actual radio buttons */
        .toggle-block input[type="radio"] {
            display: none;
        }

        a {
            color: #000 !important;
        }

        .fc .fc-button-primary {
            background-color: #18ba60 !important;
            border-color: white;
            color: white;
        }

        /*------------------------*/
        input:focus,
        button:focus,
        .form-control:focus {
            outline: none;
            box-shadow: none;
        }

        .form-control:disabled,
        .form-control[readonly] {
            background-color: #fff;
            pointer-events: none;
            user-select: none;
        }

        /*----------step-wizard------------*/
        .d-flex {
            display: flex;
        }

        .justify-content-center {
            justify-content: center;
        }

        .align-items-center {
            align-items: center;
        }

        /*---------signup-step-------------*/
        .bg-color {
            background-color: #333;
        }

        .signup-step-container {
            padding: 150px 0px;
            padding-bottom: 60px;
        }

        .wizard .nav-tabs {
            position: relative;
            margin-bottom: 0;
            border-bottom-color: transparent;
        }

        .wizard>div.wizard-inner {
            position: relative;
            margin-bottom: 50px;
            text-align: center;
        }

        .connecting-line {
            height: 2px;
            background: #e0e0e0;
            position: absolute;
            width: 60%;
            margin: 0 auto;
            left: -10%;
            right: 0;
            top: 15px;
            z-index: 1;
        }

        .wizard .nav-tabs>li.active>a,
        .wizard .nav-tabs>li.active>a:hover,
        .wizard .nav-tabs>li.active>a:focus {
            color: #555555;
            cursor: default;
            border: 0;
            border-bottom-color: transparent;
        }

        span.round-tab {
            width: 30px;
            height: 30px;
            line-height: 30px;
            display: inline-block;
            border-radius: 50%;
            background: #fff;
            z-index: 2;
            position: absolute;
            left: 0;
            text-align: center;
            font-size: 16px;
            color: #0e214b;
            font-weight: 500;
            border: 1px solid #ddd;
        }

        .disabled {
            pointer-events: none;
            user-select: none;
            background-color: #777 !important;
        }

        span.round-tab i {
            color: #555555;
        }

        .wizard li.active span.round-tab {
            background: #0db02b;
            color: #fff;
            border-color: #0db02b;
        }

        .wizard li.active span.round-tab i {
            color: #5bc0de;
        }

        .wizard .nav-tabs>li.active>a i {
            color: #0db02b;
        }

        .wizard .nav-tabs>li {
            width: 30%;
        }

        .wizard li:after {
            content: " ";
            position: absolute;
            left: 46%;
            opacity: 0;
            margin: 0 auto;
            bottom: 0px;
            border: 5px solid transparent;
            border-bottom-color: red;
            transition: 0.1s ease-in-out;
        }

        .wizard .nav-tabs>li a {
            width: 30px;
            height: 30px;
            margin: 20px auto;
            border-radius: 100%;
            padding: 0;
            background-color: transparent;
            position: relative;
            top: 0;
        }

        .wizard .nav-tabs>li a i {
            position: absolute;
            top: -15px;
            font-style: normal;
            font-weight: 400;
            white-space: nowrap;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 12px;
            font-weight: 700;
            color: #000;
        }

        .wizard .nav-tabs>li a:hover {
            background: transparent;
        }

        .wizard .tab-pane {
            position: relative;
            padding-top: 20px;
        }


        .wizard h3 {
            margin-top: 0;
        }



        .prev-step,
        .next-step {
            font-size: 13px;
            padding: 8px 24px;
            border: none;
            border-radius: 4px;
            margin-top: 30px;
        }

        .next-step {
            background-color: #008000;
            color: #fff;
            transition: 0.3s ease;
        }

        .next-step:hover {
            background-color: rgba(0, 128, 0, 0.8);
        }

        .step-head {
            font-size: 20px;
            text-align: center;
            font-weight: 500;
            margin-bottom: 20px;
        }

        .term-check {
            font-size: 14px;
            font-weight: 400;
        }

        .custom-file {
            position: relative;
            display: inline-block;
            width: 100%;
            height: 40px;
            margin-bottom: 0;
        }

        .custom-file-input {
            position: relative;
            z-index: 2;
            width: 100%;
            height: 40px;
            margin: 0;
            opacity: 0;
        }

        .custom-file-label {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1;
            height: 40px;
            padding: .375rem .75rem;
            font-weight: 400;
            line-height: 2;
            color: #495057;
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: .25rem;
        }

        .custom-file-label::after {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            z-index: 3;
            display: block;
            height: 38px;
            padding: .375rem .75rem;
            line-height: 2;
            color: #495057;
            content: "Browse";
            background-color: #e9ecef;
            border-left: inherit;
            border-radius: 0 .25rem .25rem 0;
        }

        .footer-link {
            margin-top: 30px;
        }

        .list-content {
            margin-bottom: 10px;
        }

        .list-content a {
            padding: 10px 15px;
            width: 100%;
            display: inline-block;
            background-color: #f5f5f5;
            position: relative;
            color: #565656;
            font-weight: 400;
            border-radius: 4px;
        }

        .list-content a[aria-expanded="true"] i {
            transform: rotate(180deg);
        }

        .list-content a i {
            text-align: right;
            position: absolute;
            top: 15px;
            right: 10px;
            transition: 0.5s;
        }

        .form-control[disabled],
        .form-control[readonly],
        fieldset[disabled] .form-control {
            background-color: #fdfdfd;
            pointer-events: none;
            user-select: none;
        }

        .list-box {
            padding: 10px;
        }

        .signup-logo-header .logo_area {
            width: 200px;
        }

        .signup-logo-header .nav>li {
            padding: 0;
        }

        .signup-logo-header .header-flex {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .list-inline li {
            display: inline-block;
        }

        .pull-right {
            float: right;
        }

        input[type="checkbox"] {
            position: relative;
            display: inline-block;
            margin-right: 5px;
        }

        input[type="checkbox"]::before,
        input[type="checkbox"]::after {
            position: absolute;
            content: "";
            display: inline-block;
        }

        input[type="checkbox"]::before {
            height: 16px;
            width: 16px;
            border: 1px solid #999;
            left: 0px;
            top: 0px;
            background-color: #fff;
            border-radius: 2px;
        }

        input[type="checkbox"]::after {
            height: 5px;
            width: 9px;
            left: 4px;
            top: 4px;
        }

        input[type="checkbox"]:checked::after {
            content: "";
            border-left: 1px solid #fff;
            border-bottom: 1px solid #fff;
            transform: rotate(-45deg);
        }

        input[type="checkbox"]:checked::before {
            background-color: #18ba60;
            border-color: #18ba60;
        }

        @media (max-width: 767px) {
            .sign-content h3 {
                font-size: 40px;
            }

            .wizard .nav-tabs>li a i {
                display: none;
            }

            .signup-logo-header .navbar-toggle {
                margin: 0;
                margin-top: 8px;
            }

            .signup-logo-header .logo_area {
                margin-top: 0;
            }

            .signup-logo-header .header-flex {
                display: block;
            }
        }
    </style>

</head>

<body>

    <?php require "header.php"; ?>

    <div class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="heading bold">Appointment</h1>
                </div>
            </div>
        </div>
        <div class="breadcrumb-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="breadcrumb">
                            <li><a class="text-white" href="index.php">Home</a></li>
                            <li class="active text-white">Appointment</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-80 mb-80">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="heading-block">
                    <h3 class="heading font-bold text-lh-4">Book Appointment</h3>
                    <p class="sub-heading">Lorem ipsum dolor sit amet consectetur adipisicing elit. Consectetur, ea.</p>
                </div>
            </div>
        </div>
        <div class="row mt-80">
            <div class="col-lg-12">
                <div class="wizard">
                    <div class="wizard-inner">
                        <div class="connecting-line"></div>
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" aria-expanded="true"><span class="round-tab"> </span> <i style="font-size: 20px; margin-left: 20px;">Select Schedule</i></a>
                            </li>
                            <li role="presentation" class="disabled">
                                <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" aria-expanded="false"><span class="round-tab"></span> <i style="font-size: 20px; margin-left: 20px;">Update an account</i></a>
                            </li>
                            <li role="presentation" class="disabled">
                                <a href="#step3" data-toggle="tab" aria-controls="step3" role="tab"><span class="round-tab"></span> <i style="font-size: 20px; margin-left: 20px;">Make Payment</i></a>
                            </li>
                        </ul>
                    </div>

                    <form class="login-box" method="POST" id="appointmentForm" enctype="multipart/form-data">
                        <div class="tab-content" id="main_form">
                            <div class="tab-pane active" role="tabpanel" id="step1">
                                <div class="row">
                                    <div class="col-md-7">
                                        <input type="hidden" name="serviceId" id="serviceId" value="<?= $serviceId ?>">
                                        <input type="hidden" name="serviceName" value="<?= $fetchService['service']; ?>">
                                        <input type="hidden" name="serviceCost" value="<?= $fetchService['cost']; ?>">
                                        <input type="hidden" name="serviceID" value="<?= $fetchService['id']; ?>">
                                        <div class="card shadow-sm">
                                            <div class="card-body">
                                                <div id='calendar' style="max-width: 900px; margin: 0 auto;"></div>
                                                <input type="hidden" name="selectedDate" id="selectedDay" placeholder="Selected day here.">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="card shadow-sm">
                                            <div class="card-body">
                                                <p>1. Select your preferred schedule</p>
                                                <div id="timeSlotsContainer">
                                                    <!-- Time slots will be loaded here dynamically -->
                                                    Please select a date first.

                                                </div>

                                            </div>
                                        </div>
                                        <div class="card shadow-sm mt-40">
                                            <div class="card-body">
                                                <p>2. Enter your preferred time* (8:00 AM to 5:00 PM)</p>
                                                <div id="error-time" class="bg-danger p-2 text-white my-3" style="display: none;">Please select a time between 8:00 AM and 5:00 PM</div>
                                                <input type="time" name="selectedTime" id="preferred-time" min="08:00" max="16:30" required class="form-control">

                                                <button type="button" class="default-btn next-step">Next</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" role="tabpanel" id="step2">
                                <h4 class="text-center">Update Account</h4>
                                <p class="text-center mb-3">Complete your account details to continue with booking.</p>
                                <?php
                                $user_email = $_SESSION['user_email'];
                                $selectPatient = $pdo->query("SELECT * FROM patient WHERE email = '$user_email'");
                                $fetchPatient = $selectPatient->fetch(PDO::FETCH_ASSOC);
                                ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>First Name <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="firstname" value="<?= $fetchPatient['firstname'] ?>" placeholder="Enter your first name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Last Name <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="lastname" value="<?= $fetchPatient['lastname'] ?>" placeholder="Enter your last name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Date of Birth <span class="text-danger">*</span></label>
                                            <input class="form-control" type="date" value="<?= $fetchPatient['dob'] ?>" name="birthdate" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Birthplace <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="birthplace" value="<?= $fetchPatient['birthplace'] ?>" placeholder="Enter your birthplace" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Mobile Number <span class="text-danger">*</span></label>
                                            <input class="form-control" type="number" name="contactNumber" value="<?= $fetchPatient['contact'] ?>" placeholder="Enter your mobile number" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Email Address <span class="text-danger">*</span></label>
                                            <input class="form-control" type="email" name="email" value="<?= $fetchPatient['email'] ?>" placeholder="Enter your email address" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Province <span class="text-danger">*</span></label>
                                            <select id="province" class="form-control" required></select>
                                            <input type="hidden" id="provinceName" class="form-control" name="province" placeholder="Province Name" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>City <span class="text-danger">*</span></label>
                                            <select id="city" class="form-control" required></select>
                                            <input type="hidden" id="cityName" class="form-control" name="city" placeholder="City Name" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Barangay <span class="text-danger">*</span></label>
                                            <select id="barangay" class="form-control" required></select>
                                            <input type="hidden" id="barangayName" class="form-control" name="barangay" placeholder="Barangay Name" readonly required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Street, Village/Apartment No, Landmark <span class="text-danger">*</span></label>
                                            <input type="text" placeholder="Enter your street, village/apartment no, landmark here" value="<?= $fetchPatient['street'] ?>" name="street" class="form-control" required />
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="default-btn next-step">Next</button>
                            </div>
                            <div class="tab-pane" role="tabpanel" id="step3">
                                <h4 class="text-center">Account Verified</h4>
                                <p class="text-center mb-3">Please proceed with the payment to confirm your booking.</p>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Choose payment method: <span class="text-danger">*</span></label>

                                            <?php
                                            $selectPayment = $pdo->query("SELECT * FROM payment_mode");
                                            $fetchPayment = $selectPayment->fetchAll(PDO::FETCH_ASSOC);
                                            ?>
                                            <?php foreach ($fetchPayment as $payment) : ?>
                                                <div class="form-check">
                                                    <input
                                                        type="radio"
                                                        name="selectedPayment"
                                                        value="<?= $payment['id'] ?>"
                                                        data-method="<?= $payment['method'] ?>"
                                                        data-image="admin/modals/<?= $payment['image_path'] ?>"
                                                        required
                                                        onclick="handlePaymentChange(this)">
                                                    <label><?= $payment['method'] ?></label>
                                                </div>
                                            <?php endforeach; ?>

                                            <div id="paymentDetails" style="display: none; margin-top: 20px;">
                                                <h4 id="paymentImage"></h4>
                                                <p>Upload your receipt here:</p>
                                                <input type="file" name="receipt" id="receiptInput" required>
                                                <label for="amount">Input amount you have sent.</label>
                                                <input type="text" name="amount" id="amount">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Medical Condition <span class="text-danger">*</span></label>
                                            <input class="form-control" required type="text" name="medical" placeholder="Kindly list any existing medical condition that our staff needs to be aware of. Type N/A if none.">
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" name="add_appointment" class="default-btn next-step">Submit</button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php require "footer.php"; ?>

    <div id="back"><i class="ion-chevron-up-sharp"></i></div>

    <!-- JQuery Version 3.6.0 -->
    <script src="assets/js/jquery.min.js"></script>

    <!-- Bootstrap Version 4.5.3 -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery UI (Date Picker) -->
    <script src="assets/js/jquery-ui.min.js"></script>

    <!-- Slick Slider Version 1.8.1 -->
    <script src="assets/js/slick.min.js"></script>

    <!-- Appear JS -->
    <script src="assets/js/jquery.appear.min.js"></script>

    <!-- Count To JS -->
    <script src="assets/js/jquery.countTo.min.js"></script>
    <script src="assets/js/sweetalert.js"></script>

    <!-- Custom JS -->
    <script src="assets/js/script.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all tab panels and "Next" buttons
            const tabs = document.querySelectorAll('.tab-pane');
            const nextButtons = document.querySelectorAll('.next-step');

            // Function to validate the current tab
            function validateTab(tab) {
                const requiredFields = tab.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('is-invalid'); // Add invalid class
                    } else {
                        field.classList.remove('is-invalid'); // Remove invalid class
                    }
                });

                return isValid;
            }

            // Add validation for "Next" buttons
            nextButtons.forEach((button, index) => {
                button.addEventListener('click', function() {
                    const currentTab = tabs[index];

                    // Validate the current tab
                    if (validateTab(currentTab)) {
                        // Move to the next tab if valid
                        const nextTab = tabs[index + 1];
                        if (nextTab) {
                            currentTab.classList.remove('active');
                            nextTab.classList.add('active');
                        }
                    } else {
                        // Show error message for invalid fields
                        Swal.fire({
                            title: 'Incomplete Form',
                            text: 'Please fill out all required fields.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            // Add real-time validation for all required fields
            document.querySelectorAll('[required]').forEach(field => {
                field.addEventListener('input', function() {
                    const currentTab = field.closest('.tab-pane');
                    const currentButton = currentTab.querySelector('.next-step');

                    // Enable or disable the "Next" button based on validation
                    if (validateTab(currentTab)) {
                        currentButton.removeAttribute('disabled');
                    } else {
                        currentButton.setAttribute('disabled', 'true');
                    }
                });
            });

            // Initial check to disable buttons on page load
            tabs.forEach(tab => {
                const button = tab.querySelector('.next-step');
                if (button) {
                    button.setAttribute('disabled', 'true');
                }
            });
        });
    </script>
    <script>
        function handlePaymentChange(input) {
            const method = input.getAttribute('data-method');
            const imagePath = input.getAttribute('data-image');
            const paymentDetails = document.getElementById('paymentDetails');
            const paymentImage = document.getElementById('paymentImage');
            const receiptInput = document.getElementById('receiptInput');

            // If the method is not 'cash', show the payment details and the receipt input
            if (method.toLowerCase() !== 'cash') {
                paymentImage.innerHTML = `<img src="${imagePath}" alt="Payment Method Image" style="max-width: 100px;">`;
                paymentDetails.style.display = 'block';
                receiptInput.setAttribute('required', 'required'); // Make receipt input required
            } else {
                // If the method is 'cash', hide the payment details and make receipt input not required
                paymentDetails.style.display = 'none';
                receiptInput.removeAttribute('required'); // Remove required attribute from receipt input
            }
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                themeSystem: 'bootstrap5',
                initialView: 'dayGridMonth',
                selectable: true,
                dateClick: function(info) {
                    var selectedDayInput = document.getElementById("selectedDay");
                    if (selectedDayInput) {
                        selectedDayInput.value = info.dateStr;

                        // Fetch time slots via AJAX
                        fetchTimeSlots(info.dateStr);
                    }
                }
            });
            calendar.render();

            // Add submit listener for the form
            var form = document.getElementById("appointmentForm"); // Adjust this to your actual form ID
            form.addEventListener('submit', function(event) {
                var selectedSchedule = document.querySelector('input[name="selectedSchedule"]:checked');

                // Check if a time slot is selected and if the select field is valid
                if (!selectedSchedule) {
                    event.preventDefault(); // Prevent form submission
                    Swal.fire({
                        title: 'Error!',
                        text: 'Please select a time slot.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        function fetchTimeSlots(selectedDate) {
            // Make an AJAX request to fetch time slots based on the selected date
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Parse the JSON response
                    var slotsData = JSON.parse(this.responseText);
                    var timeSlotsContainer = document.getElementById("timeSlotsContainer");

                    // Check if the time slots container exists
                    if (!timeSlotsContainer) {
                        console.error("Time slots container not found.");
                        return;
                    }

                    // Clear the container
                    timeSlotsContainer.innerHTML = '<i id="notice">Note: click to select the slot</i>';

                    if (slotsData.length > 0) {
                        // Loop through each slot data and create the HTML elements dynamically
                        slotsData.forEach(function(slotData) {
                            var slotDiv = document.createElement('div');
                            slotDiv.className = `toggle-block morning`;

                            slotDiv.innerHTML = `
                            <input type="radio" name="selectedSchedule" value="${slotData['id']}">
                            <strong>${slotData['schedule']} (${slotData['slot']} slots available)</strong><br>
                            Doctor: ${slotData['doctor_name']}
                        `;
                            timeSlotsContainer.appendChild(slotDiv);

                            // Attach event listener to the newly created slot
                            slotDiv.addEventListener('click', function() {
                                // Unselect all blocks
                                document.querySelectorAll('.toggle-block').forEach(b => b.classList.remove('selected'));
                                // Select the clicked block
                                this.classList.add('selected');
                                // Uncheck all radio buttons
                                document.querySelectorAll('input[type="radio"]').forEach(input => input.checked = false);
                                // Check the corresponding radio button
                                const radio = this.querySelector('input[type="radio"]');
                                if (radio) {
                                    radio.checked = true;
                                }
                            });
                        });
                    } else {
                        // Display the error message
                        timeSlotsContainer.innerHTML = 'No available slots for the selected date.';
                    }
                } else {
                    // Display the error message
                    var timeSlotsContainer = document.getElementById("timeSlotsContainer");
                    if (timeSlotsContainer) {
                        timeSlotsContainer.innerHTML = 'Error fetching time slots.';
                    }
                }
            };
            const serviceId = document.getElementById("serviceId").value; // Assuming there's an input or element with the service ID
            xhttp.open("GET", "fetch_time_slots.php?selectedDate=" + selectedDate + "&service_id=" + serviceId, true);
            xhttp.send();
        }
    </script>



    <script>
        // ------------step-wizard-------------
        $(document).ready(function() {
            $('.nav-tabs > li a[title]').tooltip();

            //Wizard
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {

                var target = $(e.target);

                if (target.parent().hasClass('disabled')) {
                    return false;
                }
            });

            $(".next-step").click(function(e) {

                var active = $('.wizard .nav-tabs li.active');
                active.next().removeClass('disabled');
                nextTab(active);

            });
            $(".prev-step").click(function(e) {

                var active = $('.wizard .nav-tabs li.active');
                prevTab(active);

            });
        });

        function nextTab(elem) {
            $(elem).next().find('a[data-toggle="tab"]').click();
        }

        function prevTab(elem) {
            $(elem).prev().find('a[data-toggle="tab"]').click();
        }


        $('.nav-tabs').on('click', 'li', function() {
            $('.nav-tabs li.active').removeClass('active');
            $(this).addClass('active');
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const preferredTimeInput = document.getElementById('preferred-time');
            preferredTimeInput.addEventListener('change', function() {
                const selectedTime = new Date(`2000-01-01T${this.value}`);
                const minTime = new Date(`2000-01-01T08:00`);
                const maxTime = new Date(`2000-01-01T16:30`);
                if (selectedTime < minTime || selectedTime > maxTime) {
                    document.getElementById("error-time").style.display = 'block';
                    this.value = ''; // Clear the input value
                } else {
                    document.getElementById("error-time").style.display = 'none';
                }
            });
        });
    </script>

    <script>
        // Function to fetch cities based on the selected province
        function fetchCities(provinceCode) {
            $.getJSON(`https://psgc.gitlab.io/api/provinces/${provinceCode}/cities-municipalities/`, function(data) {
                console.log(data); // Log the response to see if the cities are returned correctly
                $('#city').empty(); // Clear existing options
                $.each(data, function(index, city) {
                    $('#city').append($('<option>', {
                        value: city.code,
                        text: city.name
                    }));
                });
            }).fail(function(jqxhr, textStatus, error) {
                var err = textStatus + ", " + error;
                console.log("Request Failed: " + err);
            });

        }

        // Function to fetch barangays based on the selected city
        function fetchBarangays(cityCode) {
            $.getJSON(`https://psgc.gitlab.io/api/cities-municipalities/${cityCode}/barangays/`, function(data) {
                $('#barangay').empty(); // Clear existing options

                // Loop through the data and append options to the barangay dropdown
                $.each(data, function(index, barangay) {
                    $('#barangay').append($('<option>', {
                        value: barangay.code,
                        text: barangay.name
                    }));
                });

                // Automatically select the first option if available
                if (data.length > 0) {
                    $('#barangay').prop('selectedIndex', 0).change();
                }
            }).fail(function(jqxhr, textStatus, error) {
                var err = textStatus + ", " + error;
                console.log("Request Failed: " + err);
            });
        }


        // Wait for the document to be ready
        $(document).ready(function() {
            // Fetch data for provinces and populate the province dropdown
            $.getJSON('https://psgc.gitlab.io/api/regions/040000000/provinces/', function(data) {
                // Loop through the data and append options to the province dropdown
                $.each(data, function(index, province) {
                    $('#province').append($('<option>', {
                        value: province.code,
                        text: province.name
                    }));
                });

                // Trigger change event for the province dropdown to fetch cities for the initially selected province
                $('#province').change(function() {
                    var selectedProvinceCode = $(this).val();
                    fetchCities(selectedProvinceCode);
                    var selectedProvinceName = $(this).find('option:selected').text();
                    $('#provinceName').val(selectedProvinceName);
                }).change(); // Trigger change event initially

                // Trigger change event for the city dropdown to fetch barangays for the initially selected city
                $('#city').change(function() {
                    var selectedCityCode = $(this).val();
                    fetchBarangays(selectedCityCode);
                    var selectedCityName = $(this).find('option:selected').text();
                    $('#cityName').val(selectedCityName);
                }).change(); // Trigger change event initially

                // Trigger change event for the barangay dropdown to populate the barangay name input
                $('#barangay').change(function() {
                    var selectedBarangayName = $(this).find('option:selected').text();
                    $('#barangayName').val(selectedBarangayName);
                });
            }).fail(function(jqxhr, textStatus, error) {
                var err = textStatus + ", " + error;
                console.log("Request Failed: " + err);
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