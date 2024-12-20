<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id = $_POST['service_id'] ?? null; // Use service_id directly
    $start_date = $_POST['start_date'] ?? null;
    $end_date = $_POST['end_date'] ?? null;

    if (!$service_id || !$start_date || !$end_date) {
        echo json_encode([]); // Return empty array if required parameters are missing
        exit;
    }

    try {
        // Fetch the department associated with the selected service
        $departmentQuery = "
            SELECT department_id FROM services WHERE id = :service_id
        ";
        $stmt = $pdo->prepare($departmentQuery);
        $stmt->execute([':service_id' => $service_id]);
        $department = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$department) {
            echo json_encode([]); // If no department is found for the service, return empty
            exit;
        }

        $department_id = $department['department_id'];

        // Fetch appointment slots for doctors in the selected department and within the given date range
        $query = "
            SELECT 
                appointment_slots.id AS appointment_slot_id, 
                appointment_slots.date, 
                appointment_slots.slot, 
                doctor.employee_id AS doctor_id,
                CONCAT(doctor.firstname, ' ', doctor.lastname) AS doctor_name,
                doctor.department_id,
                departments.name AS department_name
            FROM 
                appointment_slots
            JOIN 
                doctor ON doctor.employee_id = appointment_slots.doctor_id
            JOIN 
                departments ON departments.id = doctor.department_id
            WHERE 
                appointment_slots.is_archive = 0
                AND appointment_slots.date BETWEEN :start_date AND :end_date
                AND doctor.department_id = :department_id
        ";

        // Execute query with the provided dates and department ID
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':start_date' => $start_date,
            ':end_date' => $end_date,
            ':department_id' => $department_id
        ]);
        
        // Fetch the appointment slots and return them as JSON
        $slots = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($slots);

    } catch (Exception $e) {
        echo json_encode([]); // Return empty array in case of any error
        error_log($e->getMessage()); // Log the error for debugging
    }
}
