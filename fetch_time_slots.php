<?php

require "db.php";

if (isset($_GET['selectedDate']) && isset($_GET['service_id'])) {
    $selectedDate = $_GET['selectedDate'];
    $serviceId = $_GET['service_id'];

    // Get the department of the selected service
    $serviceQuery = $pdo->prepare("SELECT department_id FROM services WHERE id = ?");
    $serviceQuery->execute([$serviceId]);
    $service = $serviceQuery->fetch(PDO::FETCH_ASSOC);

    if ($service) {
        $departmentId = $service['department_id'];

        // Fetch available time slots along with doctor details for the selected date and department
        $slotQuery = $pdo->prepare("
            SELECT 
                appointment_slots.id, 
                appointment_slots.schedule, 
                appointment_slots.slot, 
                CONCAT(doctor.firstname, ' ', doctor.lastname) AS doctor_name
            FROM appointment_slots
            JOIN doctor ON appointment_slots.doctor_id = doctor.employee_id
            WHERE appointment_slots.date = ? 
            AND doctor.department_id = ?
            AND appointment_slots.is_archive = 0
        ");
        $slotQuery->execute([$selectedDate, $departmentId]);
        $slotFetchAll = $slotQuery->fetchAll(PDO::FETCH_ASSOC);

        if ($slotFetchAll) {
            // Encode the time slots data into JSON format
            echo json_encode($slotFetchAll);
        } else {
            // If no slots available, return a JSON object with a message
            echo json_encode(array('message' => 'No available slots for the selected date.'));
        }
    } else {
        echo json_encode(array('message' => 'Service not found.'));
    }
} else {
    echo json_encode(array('message' => 'Invalid request.'));
}
?>
