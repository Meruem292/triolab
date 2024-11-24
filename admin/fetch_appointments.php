<?php
// Include your database connection
include('db.php');

header('Content-Type: application/json');

// Prepare the SQL query to fetch appointment details along with decoded values
$sql = "
    SELECT a.id, a.appointment_date, a.appointment_time, a.status, a.patient_id, a.doctor_id, a.service_id, a.department_id,
           p.firstname AS patient_firstname, p.lastname AS patient_lastname,
           d.firstname AS doctor_firstname, d.lastname AS doctor_lastname,
           s.service AS service_name, dept.name AS department_name
    FROM appointment a
    LEFT JOIN patient p ON a.patient_id = p.id
    LEFT JOIN doctor d ON a.doctor_id = d.employee_id
    LEFT JOIN services s ON a.service_id = s.id
    LEFT JOIN departments dept ON a.department_id = dept.id
    WHERE a.is_archive = 0";  // Ensure you're getting non-archived records

// Execute the query
$stmt = $pdo->prepare($sql);
$stmt->execute();
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare the data for FullCalendar
$calendar_events = [];

foreach ($appointments as $appointment) {
    $calendar_events[] = [
        'id' => $appointment['id'],
        'title' => $appointment['service_name'],
        'start' => $appointment['appointment_date'] . 'T' . $appointment['appointment_time'],
        'end' => $appointment['appointment_date'] . 'T' . date('H:i:s', strtotime($appointment['appointment_time']) + 3600), // Assuming 1-hour duration
        'patient_name' => $appointment['patient_firstname'] . ' ' . $appointment['patient_lastname'],
        'doctor_name' => $appointment['doctor_firstname'] . ' ' . $appointment['doctor_lastname'],
        'status' => $appointment['status'],
        'department_name' => $appointment['department_name'],
        'service_name' => $appointment['service_name'],
        'patient_id' => $appointment['patient_id'],
        'doctor_id' => $appointment['doctor_id'],
        'service_id' => $appointment['service_id'],
        'department_id' => $appointment['department_id']
    ];
}

// Return the JSON data to FullCalendar
echo json_encode($calendar_events);
?>
