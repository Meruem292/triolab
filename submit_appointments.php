<?php
session_start();
require 'db.php';

$user_id = $_SESSION['user_id'] ?? 10; // Replace with actual session-based user ID logic

// Check if appointments data is provided
if (isset($_POST['appointments']) && !empty($_POST['appointments'])) {
    // Decode JSON data if sent as a string
    $appointments = is_string($_POST['appointments']) ? json_decode($_POST['appointments'], true) : $_POST['appointments'];

    // Validate that appointments is an array
    $missingFields = [];
    foreach ($appointments as $appointment) {
        if (empty($appointment['serviceId'])) $missingFields[] = 'serviceId';
        if (empty($appointment['date'])) $missingFields[] = 'date';
        if (empty($appointment['time'])) $missingFields[] = 'time';
        if (empty($appointment['doctorId'])) $missingFields[] = 'doctorId';
        if (empty($appointment['slotId'])) $missingFields[] = 'slotId';
        if (empty($appointment['departmentId'])) $missingFields[] = 'departmentId';
    }

    if (!empty($missingFields)) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields: ' . implode(', ', $missingFields)]);
        exit;
    }

    try {
        // Begin transaction to ensure all operations are atomic
        $pdo->beginTransaction();

        // Prepare the SQL statement for inserting appointments
        $stmtInsertAppointment = $pdo->prepare(
            "INSERT INTO appointment (service_id, patient_id, appointment_date, appointment_time, doctor_id, department_id, appointment_slot_id, status, date_added)
            VALUES (:service_id, :patient_id, :appointment_date, :appointment_time, :doctor_id, :department_id, :appointment_slot_id, 'pending', NOW())"
        );

        // Prepare the SQL statement for inserting medical records
        $stmtInsertMedicalRecord = $pdo->prepare(
            "INSERT INTO medical_records (patient_id, doctor_id, diagnosis, treatment, record_date, created_at)
            VALUES (:patient_id, :doctor_id, NULL, NULL, NOW(), NOW())"
        );

        foreach ($appointments as $appointment) {
            // Insert appointment details
            $stmtInsertAppointment->execute([
                ':service_id' => $appointment['serviceId'],
                ':patient_id' => $user_id, // Assuming user_id from session
                ':appointment_date' => $appointment['date'],
                ':appointment_time' => $appointment['time'],
                ':doctor_id' => $appointment['doctorId'],
                ':department_id' => $appointment['departmentId'],
                ':appointment_slot_id' => $appointment['slotId']
            ]);

            // Insert related medical record (optional, depending on your use case)
            $stmtInsertMedicalRecord->execute([
                ':patient_id' => $user_id,
                ':doctor_id' => $appointment['doctorId'],
            ]);
        }

        // Commit the transaction
        $pdo->commit();

        echo json_encode(['success' => true, 'message' => 'Appointments submitted successfully!']);
    } catch (Exception $e) {
        // Rollback the transaction if any error occurs
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error submitting appointments: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No appointments data received.']);
}
?>
