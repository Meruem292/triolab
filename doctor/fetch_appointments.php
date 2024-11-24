<?php
// Include your database connection
include('db.php');
session_start();

header('Content-Type: application/json');

// Check if user_id is set in the session
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not authenticated']);
    exit;
}

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
    WHERE a.is_archive = 0 AND d.employee_id = :doctor_id";  // Corrected doctor_id field

// Execute the query with the doctor_id parameter
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':doctor_id', $_SESSION['user_id'], PDO::PARAM_INT); // Bind the session doctor ID
$stmt->execute();

// Fetch all appointment details
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
