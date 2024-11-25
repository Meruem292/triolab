<?php
// Include database connection
include 'db.php';

if (isset($_GET['record_id'])) {
    $record_id = intval($_GET['record_id']);  // Sanitize input

    // Query to fetch the medical record along with related details
    $query = "
        SELECT 
            mr.id AS record_id,
            mr.patient_id,
            mr.appointment_id,
            mr.diagnosis,
            mr.treatment,
            mr.prescription,
            mr.status AS record_status,
            mr.created_at AS record_created_at,
            mr.updated_at AS record_updated_at,
            p.firstname AS patient_firstname,
            p.lastname AS patient_lastname,
            p.contact AS patient_contact,
            p.email AS patient_email,
            a.appointment_date,
            a.appointment_time,
            aslot.schedule AS appointment_schedule,
            d.firstname AS doctor_firstname,
            d.lastname AS doctor_lastname,
            s.service AS appointment_service,
            d.department_id,
            dep.name AS department_name
        FROM medical_records mr
        LEFT JOIN patient p ON mr.patient_id = p.id
        LEFT JOIN appointment a ON mr.appointment_id = a.id
        LEFT JOIN appointment_slots aslot ON a.appointment_slot_id = aslot.id
        LEFT JOIN doctor d ON mr.doctor_id = d.employee_id
        LEFT JOIN services s ON a.service_id = s.id
        LEFT JOIN departments dep ON d.department_id = dep.id
        WHERE mr.id = :record_id
    ";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':record_id', $record_id, PDO::PARAM_INT);
    $stmt->execute();

    // Check if the record is found
    if ($stmt->rowCount() > 0) {
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Medical Record - Print</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .record-container { border: 1px solid #ccc; padding: 20px; }
                h2 { text-align: center; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { padding: 10px; text-align: left; border: 1px solid #ccc; }
            </style>
        </head>
        <body>
            <div class="record-container">
                <h2>Medical Record</h2>
                <table>
                    <tr><th>Record ID</th><td><?php echo htmlspecialchars($record['record_id']); ?></td></tr>
                    <tr><th>Patient Name</th><td><?php echo htmlspecialchars($record['patient_firstname']) . ' ' . htmlspecialchars($record['patient_lastname']); ?></td></tr>
                    <tr><th>Patient Contact</th><td><?php echo htmlspecialchars($record['patient_contact']); ?></td></tr>
                    <tr><th>Patient Email</th><td><?php echo htmlspecialchars($record['patient_email']); ?></td></tr>
                    <tr><th>Diagnosis</th><td><?php echo htmlspecialchars($record['diagnosis']); ?></td></tr>
                    <tr><th>Treatment</th><td><?php echo htmlspecialchars($record['treatment']); ?></td></tr>
                    <tr><th>Prescription</th><td><?php echo htmlspecialchars($record['prescription']); ?></td></tr>
                    <tr><th>Appointment Date</th><td><?php echo htmlspecialchars($record['appointment_date']); ?></td></tr>
                    <tr><th>Appointment Time</th><td><?php echo htmlspecialchars($record['appointment_time']); ?></td></tr>
                    <tr><th>Appointment Schedule</th><td><?php echo htmlspecialchars($record['appointment_schedule']); ?></td></tr>
                    <tr><th>Doctor Name</th><td><?php echo htmlspecialchars($record['doctor_firstname']) . ' ' . htmlspecialchars($record['doctor_lastname']); ?></td></tr>
                    <tr><th>Doctor Department</th><td><?php echo htmlspecialchars($record['department_name']); ?></td></tr>
                    <tr><th>Service</th><td><?php echo htmlspecialchars($record['appointment_service']); ?></td></tr>
                    <tr><th>Record Status</th><td><?php echo htmlspecialchars($record['record_status']); ?></td></tr>
                    <tr><th>Created At</th><td><?php echo htmlspecialchars($record['record_created_at']); ?></td></tr>
                    <tr><th>Updated At</th><td><?php echo htmlspecialchars($record['record_updated_at']); ?></td></tr>
                </table>
            </div>
            <script>
                window.onload = function() {
                    window.print();
                }
            </script>
        </body>
        </html>
        <?php
    } else {
        echo "No record found.";
    }
} else {
    echo "Invalid request.";
}
?>
