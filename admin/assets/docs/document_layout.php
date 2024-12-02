<?php
session_start();
require_once 'db.php';

$appointment_id = $_GET['appointmentId'] ?? null;

if (!$appointment_id) {
    // Handle invalid appointment ID gracefully
    $appointment = '';
    $patient = '';
    $doctor = '';
    $service = '';
    $serviceCategory = '';
    $medical_record = '';
    $appointment_count_by_services = 0;
} else {
    // Fetch appointment details
    $query = 'SELECT * FROM appointment WHERE id = :id';
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $appointment_id]);
    $appointment = $stmt->fetch();

    if ($appointment) {
        // Fetch patient details
        $query = 'SELECT * FROM patient WHERE id = :id';
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $appointment['patient_id']]);
        $patient = $stmt->fetch();

        // Fetch doctor details
        $query = 'SELECT * FROM doctor WHERE employee_id = :id';
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $appointment['doctor_id']]);
        $doctor = $stmt->fetch();

        // Fetch service details
        $query = 'SELECT * FROM services WHERE id = :id';
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $appointment['service_id']]);
        $service = $stmt->fetch();
        $serviceCategory = $service['category'] ?? '';

        // Count appointments by service type
        $query = '
            SELECT COUNT(*) AS appointment_count
            FROM appointment a
            JOIN services s ON a.service_id = s.id
            WHERE s.type = :type
            AND a.is_archive = 0'; // Optional condition to exclude archived appointments

        $stmt = $pdo->prepare($query);
        $stmt->execute(['type' => 'X-ray']);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $appointment_count_by_services = $result['appointment_count'] ?? 0;

        // Fetch medical records
        $query = 'SELECT * FROM medical_records WHERE appointment_id = :id';
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $appointment_id]);
        $medical_record = $stmt->fetch();
    } else {
        // If appointment not found, set dependent variables to ''
        $patient = '';
        $doctor = '';
        $service = '';
        $serviceCategory = '';
        $medical_record = '';
        $appointment_count_by_services = 0;
    }
}

$medical_data = json_decode($medical_record['content'], true);

$hemoglobin = $medical_data['hemoglobin'] ?? '';
$hematocrit = $medical_data['hematocrit'] ?? '';
$wbc_count = $medical_data['wbc_count'] ?? '';
$rbc_count = $medical_data['rbc_count'] ?? '';
$segmenters = $medical_data['segmenters'] ?? '';
$lymphocytes = $medical_data['lymphocytes'] ?? '';
$eosinophils = $medical_data['eosinophils'] ?? '';
$monocytes = $medical_data['monocytes'] ?? '';
$platelet_count = $medical_data['platelet_count'] ?? '';
$blood_type = $medical_data['blood_type'] ?? '';



// Set default values if they exist in the JSON data
$request_by = $medical_data['request_by'] ?? '';
$examination = $medical_data['examination'] ?? '';
$findings = $medical_data['findings'] ?? '';
$impression = $medical_data['impression'] ?? '';
$age_sex = $medical_data['age_sex'] ?? '';  // Assuming 'age_sex' is a key in your medical data
$header = $medical_data['header'] ?? '';  // Assuming 'header' is a key in your medical data
$xrayno = $medical_data['x_ray_number'] ?? '';  // Assuming 'x_ray_number' is a key in your medical data
$doctor_title = $medical_data['doctor_title'] ?? '';  // Assuming 'doctor_title' is a key in your medical data
$specialization = $medical_data['specialization'] ?? '';  // Assuming 'specialization' is a key in your medical data

if (isset($_POST['submit_doc_changes']) && $_POST['type'] == 'laboratory') {
    $form_data = [
        'hemoglobin' => filter_input(INPUT_POST, 'hemoglobin', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
        'hematocrit' => filter_input(INPUT_POST, 'hematocrit', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
        'wbc_count' => filter_input(INPUT_POST, 'wbc_count', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
        'rbc_count' => filter_input(INPUT_POST, 'rbc_count', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
        'segmenters' => filter_input(INPUT_POST, 'segmenters', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
        'lymphocytes' => filter_input(INPUT_POST, 'lymphocytes', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
        'eosinophils' => filter_input(INPUT_POST, 'eosinophils', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
        'monocytes' => filter_input(INPUT_POST, 'monocytes', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
        'platelet_count' => filter_input(INPUT_POST, 'platelet_count', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
        'blood_type' => filter_input(INPUT_POST, 'blood_type'),
        'doctor_name' => strtoupper($doctor['firstname']) . ' ' . strtoupper($doctor['lastname']),

    ];
    // Convert the form data to JSON
    $json_data = json_encode($form_data);

    // Check if the appointment already has a medical record
    $query = 'SELECT id FROM medical_records WHERE appointment_id = :appointment_id';
    $stmt = $pdo->prepare($query);
    $stmt->execute(['appointment_id' => $appointment_id]);
    $existing_record = $stmt->fetch();

    if ($existing_record) {
        // If the record exists, update it
        $update_query = 'UPDATE medical_records SET content = :content WHERE appointment_id = :appointment_id';
        $stmt = $pdo->prepare($update_query);
        $stmt->execute(['content' => $json_data, 'appointment_id' => $appointment_id]);
    } else {
        // If the record does not exist, insert a new one
        $insert_query = 'INSERT INTO medical_records (appointment_id, content, record_date) VALUES (:appointment_id, :content, NOW())';
        $stmt = $pdo->prepare($insert_query);
        $stmt->execute(['appointment_id' => $appointment_id, 'content' => $json_data]);
    }

    $_SESSION['message'] = 'Action completed successfully.';
    $_SESSION['status'] = 'success'; // Use 'error', 'warning', etc., as needed

    header("Location: ../docs/printing_layout.php?appointment_id=$appointment_id");
} elseif (isset($_POST['submit_doc_changes']) && $_POST['type'] == 'xray') {
    $form_data = [
        'date' => date('Y-m-d'),
        'x_ray_number' => filter_input(INPUT_POST, 'xrayno'), // This assumes $appointment_count_by_services is set earlier
        'patient_name' => strtoupper($patient['lastname']) . ", " . strtoupper($patient['firstname']),
        'age_sex' => $patient['age'] . '/' . $patient['sex'],  // Placeholder for age/sex (no input for this in the form)
        'address' => $patient['city'],
        'request_by' => filter_input(INPUT_POST, 'request_by'),
        'examination' => filter_input(INPUT_POST, 'examination'),
        'findings' => filter_input(INPUT_POST, 'findings'),
        'impression' => filter_input(INPUT_POST, 'impression'),
        'doctor_name' => strtoupper($doctor['firstname']) . ' ' . strtoupper($doctor['lastname']),  // Assuming "MD" is the title of the doctor
        'radiologist_signature' => strtoupper($doctor['firstname']) . ' ' . strtoupper($doctor['lastname']),
        'radiologist_position' => 'Radiologist',
        'specialization' => filter_input(INPUT_POST, 'specialization'),
        'doctor_title' => filter_input(INPUT_POST, 'doctor_title'),
        'header' => filter_input(INPUT_POST, 'header'),

    ];
    // Convert the form data to JSON
    $json_data = json_encode($form_data);

    // Check if the appointment already has a medical record
    $query = 'SELECT id FROM medical_records WHERE appointment_id = :appointment_id';
    $stmt = $pdo->prepare($query);
    $stmt->execute(['appointment_id' => $appointment_id]);
    $existing_record = $stmt->fetch();

    if ($existing_record) {
        // If the record exists, update it
        $update_query = 'UPDATE medical_records SET content = :content WHERE appointment_id = :appointment_id';
        $stmt = $pdo->prepare($update_query);
        $stmt->execute(['content' => $json_data, 'appointment_id' => $appointment_id]);
    } else {
        // If the record does not exist, insert a new one
        $insert_query = 'INSERT INTO medical_records (appointment_id, content, record_date) VALUES (:appointment_id, :content, NOW())';
        $stmt = $pdo->prepare($insert_query);
        $stmt->execute(['appointment_id' => $appointment_id, 'content' => $json_data]);
    }

    $_SESSION['message'] = 'Action completed successfully.';
    $_SESSION['status'] = 'success'; // Use 'error', 'warning', etc., as needed
    header("Location: ../docs/printing_layout.php?appointment_id=$appointment_id");
}
?>


<!DOCTYPE html>
<html lang="en" id="clearance">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script src="https://unpkg.com/unlazy@0.11.3/dist/unlazy.with-hashing.iife.js" defer init></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script type="text/javascript">
        window.tailwind.config = {
            darkMode: ['class'],
            theme: {
                extend: {
                    colors: {
                        border: 'hsl(var(--border))',
                        input: 'hsl(var(--input))',
                        ring: 'hsl(var(--ring))',
                        background: 'hsl(var(--background))',
                        foreground: 'hsl(var(--foreground))',
                        primary: {
                            DEFAULT: 'hsl(var(--primary))',
                            foreground: 'hsl(var(--primary-foreground))'
                        },
                        secondary: {
                            DEFAULT: 'hsl(var(--secondary))',
                            foreground: 'hsl(var(--secondary-foreground))'
                        },
                        destructive: {
                            DEFAULT: 'hsl(var(--destructive))',
                            foreground: 'hsl(var(--destructive-foreground))'
                        },
                        muted: {
                            DEFAULT: 'hsl(var(--muted))',
                            foreground: 'hsl(var(--muted-foreground))'
                        },
                        accent: {
                            DEFAULT: 'hsl(var(--accent))',
                            foreground: 'hsl(var(--accent-foreground))'
                        },
                        popover: {
                            DEFAULT: 'hsl(var(--popover))',
                            foreground: 'hsl(var(--popover-foreground))'
                        },
                        card: {
                            DEFAULT: 'hsl(var(--card))',
                            foreground: 'hsl(var(--card-foreground))'
                        },
                    },
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer base {
            :root {
            --muted: 240 3.7% 15.9%;
            --muted-foreground: 240 5% 64.9%;
            --accent: 240 3.7% 15.9%;
            --accent-foreground: 0 0% 98%;
            --destructive: 0 62.8% 30.6%;
            --destructive-foreground: 0 0% 98%;
            --border: 240 3.7% 15.9%;
            --input: 240 3.7% 15.9%;
            --ring: 240 4.9% 83.9%;
        }
				.dark {
					--background: 240 10% 3.9%;
--foreground: 0 0% 98%;
--card: 240 10% 3.9%;
--card-foreground: 0 0% 98%;
--popover: 240 10% 3.9%;
--popover-foreground: 0 0% 98%;
--primary: 0 0% 98%;
--primary-foreground: 240 5.9% 10%;
--secondary: 240 3.7% 15.9%;
--secondary-foreground: 0 0% 98%;
--muted: 240 3.7% 15.9%;
--muted-foreground: 240 5% 64.9%;
--accent: 240 3.7% 15.9%;
--accent-foreground: 0 0% 98%;
--destructive: 0 62.8% 30.6%;
--destructive-foreground: 0 0% 98%;
--border: 240 3.7% 15.9%;
--input: 240 3.7% 15.9%;
--ring: 240 4.9% 83.9%;
				}
			}

          
            body {
    font-family: 'Times New Roman', Times, serif;
    margin: 0;
    padding: 0;
    background-color: white;
}

.page-container {
    width: 297mm;      /* A3 width */
    height: 420mm;     /* A4 height */
    padding: 20mm;     /* Padding inside the page */
    box-sizing: border-box;
    margin: 0 auto;
    position: relative;
}

.document-content {
    position: relative;
    z-index: 2;
}

.text-center {
    text-align: center;
}

.print-note {
    text-align: center;
    font-size: 12px;
    margin-top: 30px;
}

.action-buttons {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    justify-content: center;
    gap: 10px;
    margin: 0;
}

.btn-print {
    background-color: #4CAF50;
    color: white;
}

.btn-close {
    background-color: #f44336;
    color: white;
}
input[type="text"] {
    border: none;
    border-bottom: 1px solid black;
    width: 100px;
    margin-left: 10px;
    margin-right: 10px;
    padding: 2px 0; /* Reduced padding */
    height: 14px; /* Smaller height */
    font-size: 16px; 
    line-height: 0.5; 
    text-align: center; /* Center-align text inside the input */
}

@media print {
    body {
        margin: 0;
        padding: 0;
    }

    .noprint,
    .action-buttons {
        display: none;
    }

    .page-container {
        width: 210mm;      /* A4 width */
        height: 297mm;     /* A4 height */
        padding: 20mm;     /* Padding for the content */
        box-sizing: border-box;
        margin: 0 auto;
    }

    @page {
        size: A4;
        margin: 0;         /* Removes default margin */
    }
}
    </style>
</head>

<body>

    <div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-lg dark:bg-zinc-800" id="">
        <div class="flex items-center mb-2">
            <img src="../images/triolab_header.png" alt="logo_header" class="h-100 w-100">
        </div>


        <?php switch ($serviceCategory) {
            case 'Laboratory Services': ?>
                <div class="flex justify-between">
                    <!-- on the left -->
                    <div>
                        <p><strong>Patient Name:</strong>
                            <?= isset($patient) && $patient ? htmlspecialchars($patient['lastname'] . ', ' . $patient['firstname']) : 'N/A' ?>
                        </p>
                        <p><strong>Requested By:</strong>
                            <?= isset($doctor) && $doctor ? htmlspecialchars($doctor['firstname'] . ' ' . $doctor['lastname']) : 'N/A' ?>
                        </p>
                    </div>

                    <!-- on the right -->
                    <div style="margin-right: 10%">
                        <p><strong>Age/Sex:</strong> <?= $patient['age'] . '/' . $patient['sex'] ?></p>
                        <p><strong>Date:</strong> <?= date('Y-m-d') ?></p>
                    </div>
                </div>
                <h5 class="text-lg font-bold text-center mt-4 text-zinc-900 dark:text-zinc-100">LABORATORY REPORT</h5>
                <h2 class="text-xl font-bold text-center text-zinc-900 dark:text-zinc-100">HEMATOLOGY</h2>
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="type" value="laboratory">
                    <input type="hidden" name="appointment_id" value="<?= $appointment_id ?>">
                    <div class="flex justify-between mt-2" style="margin-bottom:200px;">
                        <!-- on the left -->
                        <div style="text-align: right;">
                            <h6 class="text-m font-bold text-zinc-900 dark:text-zinc-100">Complete Blood Count</h6>
                            <p>Hemoglobin: <input type="text" name="hemoglobin" value="<?= $hemoglobin ?>"></p>
                            <p>Hematocrit: <input type="text" name="hematocrit" value="<?= $hematocrit ?>"></p>
                            <p>WBC Count: <input type="text" name="wbc_count" value="<?= $wbc_count ?>"></p>
                            <p>RBC Count: <input type="text" name="rbc_count" value="<?= $rbc_count ?>"></p>
                            <h6 class="text-m font-bold text-zinc-900 dark:text-zinc-100">Differential Count</h6>
                            <p>Segmenters: <input type="text" name="segmenters" value="<?= $segmenters ?>"></p>
                            <p>Lymphocytes: <input type="text" name="lymphocytes" value="<?= $lymphocytes ?>"></p>
                            <p>Eosinophils: <input type="text" name="eosinophils" value="<?= $eosinophils ?>"></p>
                            <p>Monocytes: <input type="text" name="monocytes" value="<?= $monocytes ?>"></p>
                            <p>Platelet Count: <input type="text" name="platelet_count" value="<?= $platelet_count ?>"></p>
                            <h6 class="text-m font-bold text-zinc-900 dark:text-zinc-100">Others</h6>
                            <p>BLOOD TYPE: <input type="text" name="blood_type" value="<?= $blood_type ?>"></p>
                            <br>
                            <p><?= strtoupper($doctor['firstname']) . ' ' . strtoupper($doctor['lastname']) . ', ' ?> RMT</p>
                        </div>

                        <!-- on the right -->
                        <div style="margin-right: 10%">
                            <h6 class="text-m font-bold text-zinc-900 dark:text-zinc-100">Normal Values</h6>
                            <p>M: 140 - 170 g/L F: 120 - 150 g/L</p>
                            <p>M: 0.40 - 0.54 F: 0.37 - 0.47 </p>
                            <p>5 - 10 x 10<sup>9</sup>/L </p>
                            <p>3.9 - 5.5 x 10<sup>12</sup>/L </p>
                            <h6 class="text-m font-bold text-zinc-900 dark:text-zinc-100">Differential Count</h6>
                            <p>0.50 - 0.70</p>
                            <p>0.20 - 0.40</p>
                            <p>0.01 - 0.04</p>
                            <p>0.02 - 0.06</p>
                            <p>150 - 450 x 10<sup>9</sup>/L</p>
                            <br>
                            <br>
                            <br>
                            <p><ins>LUDIVINA T. SOLS, MD, FPSP</ins></p>
                        </div>
                    </div>
                <?php break;
            case 'Imaging Services': ?>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="type" value="xray">
                        <input type="hidden" name="appointment_id" value="<?= $appointment_id ?>">
                        <h1 class="text-lg font-bold text-center mt-4 text-zinc-900 dark:text-zinc-100">
                            <input type="text" class="text-xl w-50 h-100" name="header" value="<?= $header ?>">
                        </h1>
                        <div class="row mt-2">
                            <div class="col-9">
                                <!-- Left-side content -->
                            </div>
                            <!-- Right-side content -->
                            <div class="col-auto ms-auto">
                                <p>Date: <?= date('Y-m-d') ?></p>
                                <p><input type="text" name="xrayno" id="xrayno" value="<?= $xrayno ?>"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <p>Name: <strong><?= strtoupper($patient['lastname']) . ", " . strtoupper($patient['firstname']) ?></strong></p>
                                <p><strong>Age/Sex:</strong> <?= $patient['age'] . '/' . $patient['sex'] ?></p>
                                <p>Address: <?= $patient['city'] ?></p>
                                <p>Requsted by: <input type="text" name="request_by" value="<?= $request_by ?>"></p>
                                <br>
                                <p>Kind of Examination: <input type="text" name="examination" value="<?= $examination ?>"></p>
                                <p class="mt-3">Findings:</p>

                                <textarea class="mb-3 w-100" name="findings" id="findings"><?= $findings ?></textarea>
                                <p class="mb-2">IMPRESSION: </p>

                                <textarea class="w-100" name="impression" id="impression"><?= $impression ?></textarea>
                            </div>
                        </div>
                        <div class="row mt-5 mb-5">
                            <div class="col-4"></div>
                            <div class="col-8 d-flex flex-column align-items-center">
                                <p><strong><ins>
                                            <?php if (isset($doctor) && $doctor): ?>
                                                <?= strtoupper($doctor['firstname'] ?? '') . ' ' . strtoupper($doctor['lastname'] ?? ''); ?>,
                                            <?php else: ?>
                                                No Doctor Assigned,
                                            <?php endif; ?>
                                            <input type="text" style="text-align: left;" name="doctor_title" value="<?= $doctor_title ?? ''; ?>">
                                        </ins></strong></p>
                                <p>
                                    <input type="text" style="width:250px" name="specialization" value="<?= $specialization ?? ''; ?>">
                                </p>
                            </div>

                        </div>

                <?php break;
            default:
                "No service category found";
                break;
        } ?>

    </div>

    <!-- Your existing HTML content -->
    <div class="action-buttons noprint">
        <button type="submit" class="btn btn-success" name="submit_doc_changes">Submit Findings</button>
        <button type="button" class="btn btn-danger"
            onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href = '../medical-records.php'; }">
            Close
        </button>

    </div>
    </form>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        function Popup(data) {
            var mywindow = window.open('', 'my div', 'height=400,width=600');
            var printButton = document.getElementById("printpagebutton");
            // Set the print button visibility to 'hidden'
            printButton.style.visibility = 'hidden';
            mywindow.document.write(data);
            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10

            mywindow.print();

            printButton.style.visibility = 'visible';
            mywindow.close();

            return true;
        }
    </script>

</body>


</html>