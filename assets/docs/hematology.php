<?php
session_start();
require_once 'db.php';

$appointment_id = $_GET['appointmentId'] ?? null;

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

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Define the directory to save uploaded files
    $targetDir = "uploads/medical_reports/";

    // Ensure the directory exists
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Get file details
    $fileName = basename($_FILES['document']['name']);
    $targetFilePath = $targetDir . uniqid() . '_' . $fileName; // Ensure unique file name
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // Allowed file types
    $allowedTypes = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];

    if (in_array($fileType, $allowedTypes)) {
        // Attempt to upload file
        if (move_uploaded_file($_FILES['document']['tmp_name'], $targetFilePath)) {
            try {
                // Begin transaction
                $pdo->beginTransaction();

                // Update the document_path for the given appointment ID
                $stmt = $pdo->prepare("UPDATE appointment SET document_path = :document_path WHERE id = :appointment_id");
                $stmt->execute([
                    ':document_path' => $targetFilePath,
                    ':appointment_id' => $appointment_id
                ]);

                // Log the action (requires logAction function)
                logAction($pdo, 'Upload', "Document uploaded for appointment ID $appointment_id");

                // Commit transaction
                $pdo->commit();

                echo "<p class='text-success'>File uploaded and path updated successfully.</p>";
            } catch (PDOException $e) {
                $pdo->rollBack();
                echo "<p class='text-danger'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        } else {
            echo "<p class='text-danger'>File upload failed.</p>";
        }
    } else {
        echo "<p class='text-warning'>Invalid file type. Only PDF, DOC, DOCX, JPG, and PNG files are allowed.</p>";
    }
}
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

    <div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-lg dark:bg-zinc-800">
        <div class="flex items-center mb-2">
            <img src="../images/triolab_header.png" alt="logo_header" class="h-100 w-100">
        </div>

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
                <!-- <p>Age/Sex: </p> -->
                <p><strong>Date:</strong> <?= date('Y-m-d') ?></p>
            </div>
        </div>


        <h5 class="text-lg font-bold text-center mt-4 text-zinc-900 dark:text-zinc-100">LABORATORY REPORT</h5>
        <h2 class="text-xl font-bold text-center text-zinc-900 dark:text-zinc-100">HEMATOLOGY</h2>


        <div class="flex justify-between mt-2" style="margin-bottom:200px;">

            <!-- on the left -->
            <div style="text-align: right;">
                <h2 class="text-m font-bold text-zinc-900 dark:text-zinc-100">Complete Blood Count</h2>
                <p>Hemoglobin: <input type="text" name="hemoglobin"></p>
                <p>Hematocrit: <input type="text" name="hematocrit"></p>
                <p>WBC Count: <input type="text" name="wbc_count"></p>
                <p>RBC Count: <input type="text" name="rbc_count"></p>
                <h2 class="text-m font-bold text-zinc-900 dark:text-zinc-100">Differential Count</h2>
                <p>Segmenters: <input type="text" name="segmenters"></p>
                <p>Lymphocytes: <input type="text" name="lymphocytes"></p>
                <p>Eosinophils: <input type="text" name="eosinophils"></p>
                <p>Monocytes: <input type="text" name="monocytes"></p>
                <p>Platelet Count: <input type="text" name="platelet_count"></p>
                <h2 class="text-m font-bold text-zinc-900 dark:text-zinc-100">Others</h2>
                <p>BLOOD TYPE: <input type="text" name="blood_type"></p>
                <br>
                <p><?= strtoupper($doctor['firstname']) . ' ' . strtoupper($doctor['lastname']) . ', ' ?> RMT</p>
            </div>

            <!-- on the right -->
            <div style="margin-right: 10%">
                <h2 class="text-m font-bold text-zinc-900 dark:text-zinc-100">Normal Values</h2>
                <p>M: 140 - 170 g/L F: 120 - 150 g/L</p>
                <p>M: 0.40 - 0.54 F: 0.37 - 0.47 </p>
                <p>5 - 10 x 10<sup>9</sup>/L </p>
                <p>3.9 - 5.5 x 10<sup>12</sup>/L </p>
                <h2 class="text-m font-bold text-zinc-900 dark:text-zinc-100">Differential Count</h2>
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


    </div>

    <!-- Action Buttons -->
    <div class="action-buttons noprint">
        <form action="POST">
            <button type="submit" class="btn btn-success" name="submit_findings">Submit findings</button>
            <button type="button" class="btn btn-danger" onclick="window.history.back()">Close</button>
        </form>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        function PrintElem(elem) {
            window.print();
        }

        function Popup(data) {
            var mywindow = window.open('', 'my div', 'height=400,width=600');
            //mywindow.document.write('<html><head><title>my div</title>');
            /*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
            //mywindow.document.write('</head><body class="skin-black" >');
            var printButton = document.getElementById("printpagebutton");
            //Set the print button visibility to 'hidden' 
            printButton.style.visibility = 'hidden';
            mywindow.document.write(data);
            //mywindow.document.write('</body></html>');

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