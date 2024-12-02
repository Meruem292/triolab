<?php
require "db.php";

date_default_timezone_set('UTC');  // Change to your server's timezone if needed (e.g., 'Asia/Manila')
if (isset($_GET['service_id']) && isset($_GET['month']) && isset($_GET['year'])) {
    $serviceId = $_GET['service_id'];
    $month = (int) $_GET['month'];
    $year = (int) $_GET['year'];

    // Get the department of the selected service
    $serviceQuery = $pdo->prepare("SELECT department_id FROM services WHERE id = ?");
    $serviceQuery->execute([$serviceId]);
    $service = $serviceQuery->fetch(PDO::FETCH_ASSOC);

    if ($service) {
        $departmentId = $service['department_id'];

        // Fetch available slots for the department in the selected month
        $slotQuery = $pdo->prepare("
            SELECT 
                DATE_FORMAT(appointment_slots.date, '%Y-%m-%d') AS date, 
                SUM(appointment_slots.slot) AS total_slots
            FROM appointment_slots
            JOIN doctor ON appointment_slots.doctor_id = doctor.employee_id
            WHERE doctor.department_id = ?
            AND MONTH(appointment_slots.date) = ?
            AND YEAR(appointment_slots.date) = ?
            AND appointment_slots.is_archive = 0
            GROUP BY appointment_slots.date
        ");
        $slotQuery->execute([$departmentId, $month, $year]);
        $availableDates = $slotQuery->fetchAll(PDO::FETCH_ASSOC);

        // Map dates with their slot availability
        $availability = [];
        foreach ($availableDates as $date) {
            $availability[$date['date']] = (int) $date['total_slots'];
        }

        echo json_encode($availability);
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}
?>
