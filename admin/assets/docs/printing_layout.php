<?php
session_start();
require_once 'db.php';

$appointment_id = $_GET['appointment_id'] ?? null;

if (!$appointment_id) {
    die('Invalid appointment ID.');
}

// Fetch appointment details
$query = 'SELECT * FROM appointment WHERE id = :id';
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $appointment_id]);
$appointment = $stmt->fetch();

if (!$appointment) {
    die('Appointment not found.');
}

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

$query = 'SELECT * FROM services WHERE id = :id';
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $appointment['service_id']]);
$service = $stmt->fetch();
$serviceCategory = $service['category'];

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

$query = 'SELECT * FROM medical_records WHERE appointment_id = :id';
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $appointment_id]);
$medical_record = $stmt->fetch();

$medical_data = json_decode($medical_record['content'], true);

// Set default values if they exist in the JSON data
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
?>


<!DOCTYPE html>
<html lang="en" id="clearance">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script src="https://unpkg.com/unlazy@0.11.3/dist/unlazy.with-hashing.iife.js" defer init></script>
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
    width: 210mm;      /* A4 width */
    height: 297mm;     /* A4 height */
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
                        <p><strong>Date:</strong> <?= date('Y-m-d') ?></p>
                    </div>
                </div>
                <h5 class="text-lg font-bold text-center mt-4 text-zinc-900 dark:text-zinc-100">LABORATORY REPORT</h5>
                <h2 class="text-xl font-bold text-center text-zinc-900 dark:text-zinc-100">HEMATOLOGY</h2>
                <form action="" method="POST" enctype="multipart/form-data">
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
                            <input type="text" style="border: none" style="border:none;" class="text-xl w-50 h-100" name="header" value="<?= $header ?>">
                        </h1>
                        <div class="row mt-2">
                            <div class="col-9">
                                <!-- Left-side content -->
                            </div>

                            <!-- Right-side content -->
                            <div class="col-auto ms-auto">
                                <p>Date: <?= date('Y-m-d') ?></p>
                                <p><input type="text" style="border: none" name="xrayno" id="xrayno" value="<?= $xrayno ?>"></p>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <p>Name: <strong><?= strtoupper($patient['lastname']) . ", " . strtoupper($patient['firstname']) ?></strong></p>
                                <p><strong>Age/Sex:</strong> <?= $patient['age'] . '/' . $patient['sex'] ?></p>
                                <p>Address: <?= $patient['city'] ?></p>
                                <p>Requsted by: <input style="border:none;" type="text" name="request_by" value="<?= $request_by ?>"></p>
                                <br>
                                <p>Kind of Examination: <input style="border:none;" type="text" name="examination" value="<?= $examination ?>"></p>
                                <p class="mt-3">Findings:</p>

                                <textarea class="mb-3 w-100" style="border:none;" name="findings" id="findings"><?= $findings ?></textarea>
                                <p class="mb-2">IMPRESSION: </p>

                                <textarea class="w-100" style="border:none;" name="impression" id="impression"><?= $impression ?></textarea>
                            </div>
                        </div>
                        <div class="row mt-5 mb-5">
                            <div class="col-4"></div>
                            <div class="col-8 d-flex flex-column align-items-center">
                                <p class=""><strong><ins><?= strtoupper($doctor['firstname']) . ' ' . strtoupper($doctor['lastname']) . ', ' ?>
                                            <input type="text" style="border:none; text-align: left" name="doctor_title" value="<?= $doctor_title ?>"></ins></strong></p>
                                <p><input type="text" style="border:none; width:250px" name="specialization" value="<?= $specialization ?>"></p>
                            </div>
                        </div>

                <?php break;
            default:
                echo 'No service category found.';
        } ?>

    </div>

    <!-- Action Buttons -->
    <!-- Your existing HTML content -->
    <div class="action-buttons noprint">
        <button type="submit" class="btn btn-success" onclick="window.print()">Print Medical Record</button>
        <!-- <button type="button" class="btn btn-danger"
            onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href = '../../../medical-records.php'; }">
            Close
        </button> -->
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