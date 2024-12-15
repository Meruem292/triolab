<?php
session_start();
include 'db.php';

// Retrieve data from URL
$appointmentId = $_GET['appointment_id'] ?? '';
$app_id = $_GET['app_id'] ?? '';

// Fetch all appointments with the same app_id
$queryAppointment = "SELECT * FROM appointment WHERE app_id = ?";
$stmtAppointment = $pdo->prepare($queryAppointment);
$stmtAppointment->execute([$app_id]);
$appointments = $stmtAppointment->fetchAll(PDO::FETCH_ASSOC);

if (!$appointments) {
    die('Appointments not found.');
}

// Aggregate patient details (assuming they are the same for all records)
$patientId = $appointments[0]['patient_id'];
$queryPatient = "SELECT * FROM patient WHERE id = ?";
$stmtPatient = $pdo->prepare($queryPatient);
$stmtPatient->execute([$patientId]);
$patient = $stmtPatient->fetch(PDO::FETCH_ASSOC);

$email = $patient['email'];
$fname = $patient['firstname'];
$lname = $patient['lastname'];
$age = $patient['age'];
$sex = $patient['sex'];

// Static appointment details
$appointmentDate = $appointments[0]['appointment_date'];
$appointmentTime = $appointments[0]['appointment_time'];
$appointmentNote = $appointments[0]['medical'];

// Initialize aggregations
$services = [];
$doctors = [];
$departments = [];

// Process each appointment
foreach ($appointments as $appointment) {
    // Fetch service type
    $serviceName = $pdo->query("SELECT type FROM services WHERE id = " . $appointment['service_id'])->fetchColumn();
    if ($serviceName && !in_array($serviceName, $services)) {
        $services[] = $serviceName;
    }

    // Fetch doctor details
    $doctorId = $appointment['doctor_id'];
    $queryDoctor = "SELECT * FROM doctor WHERE employee_id = ?";
    $stmtDoctor = $pdo->prepare($queryDoctor);
    $stmtDoctor->execute([$doctorId]);
    $doctor = $stmtDoctor->fetch(PDO::FETCH_ASSOC);

    if ($doctor) {
        $doctorName = $doctor['firstname'] . ' ' . $doctor['lastname'];
        $doctorDepartmentId = $doctor['department_id'];

        if (!in_array($doctorName, $doctors)) {
            $doctors[] = $doctorName;
        }

        // Fetch department details
        if ($doctorDepartmentId) {
            $queryDepartment = "SELECT name FROM departments WHERE id = ?";
            $stmtDepartment = $pdo->prepare($queryDepartment);
            $stmtDepartment->execute([$doctorDepartmentId]);
            $departmentName = $stmtDepartment->fetchColumn();

            if ($departmentName && !in_array($departmentName, $departments)) {
                $departments[] = $departmentName;
            }
        }
    }
}

// Convert arrays to comma-separated strings
$servicesList = implode(', ', $services);
$doctorsList = implode(', ', $doctors);
$departmentsList = implode(', ', $departments);

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
        <div class="flex items-center">
            <img src="../images/triolab_header.png" alt="Clinic Logo" />

        </div>

        <h4 class="text-xl font-bold text-center mt-4 text-zinc-900 dark:text-zinc-100">APPOINTMENT RECEIPT</h4>
        <div class="mt-1 flex justify-end flex-col items-end space-y-0">
            <p class="text-sm text-zinc-600 dark:text-zinc-300">Appointment Date: <?php echo $appointmentDate; ?></p>
            <p class="text-sm text-zinc-600 dark:text-zinc-300">Appointment Time: <?php echo $appointmentTime; ?></p>
        </div>

        <div class="mt-2">
            <p class="font-bold text-zinc-900 dark:text-zinc-100">Patiend ID: <span class="font-normal text-zinc-800 dark:text-zinc-300">
                    <?php echo $patientId; ?>
                </span></p>
            <p class="font-bold text-zinc-900 dark:text-zinc-100">Name: <span class="font-normal text-zinc-800 dark:text-zinc-300"><?php echo strtoupper($lname) . ', ' . ucfirst($fname); ?></span></p>
            <p class="font-bold text-zinc-900 dark:text-zinc-100">Age/Sex: <span class="font-normal text-zinc-800 dark:text-zinc-300">
                    <?php echo $age . '/' . $sex; ?>
                </span></p> <!-- You can dynamically change age/sex if available -->
            <p class="font-bold text-zinc-900 dark:text-zinc-100">Address: <span class="font-normal text-zinc-800 dark:text-zinc-300"><?php echo $email; ?></span></p>
            <p class="font-bold text-zinc-900 dark:text-zinc-100">Requested Services: <span class="font-normal text-zinc-800 dark:text-zinc-300"><?php echo $servicesList; ?></span></p>
            <p class="font-bold text-zinc-900 dark:text-zinc-100 mt-4">Note: <span class="font-normal text-zinc-800 dark:text-zinc-300"><?php echo $appointmentNote; ?></span></p>
        </div>

        <div class="row mt-5">
            <div class="col-12 d-flex justify-content-center align-items-center">
                <h1 class="font-bold text-zinc-900 dark:text-zinc-100">Appointment ID: <?= $app_id ?></h1>
            </div>
        </div>


        <div class="row h-100 flex items-center justify-center mt-5" >
            <div class="col-auto">
                <div class="flex flex-col items-center justify-center text-center"  style="margin:100px 0;">
                    <p class="text-zinc-900 dark:text-zinc-100">
                        <ins><?php echo $doctorsList ?></ins>
                    </p>
                    <p class="text-zinc-900 dark:text-zinc-100">
                        <?= $departmentsList ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- Action Buttons -->
    <div class="action-buttons noprint">
        <button type="button" class="btn btn-success" onclick="window.print()">Print</button>
        <button type="button" class="btn btn-danger" onclick="window.history.back()">Close</button>
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