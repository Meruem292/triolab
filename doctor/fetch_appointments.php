<?php
// Include your database connection
include('db.php');
session_start();

header('Content-Type: application/json');

// Check if employee_id is set in the session
if (!isset($_SESSION['user_id'])) { /// i save the logged in user id in the session
    echo json_encode(['error' => 'User not authenticated']);
    exit;
}

try {
    $doctor_id = $_SESSION['user_id']; // Retrieve the logged-in doctor's ID from the session

    // SQL query to fetch detailed appointment data for the logged-in doctor
    $sql = "
        SELECT a.id, a.appointment_date, a.appointment_time, a.status, 
       CONCAT(p.firstname, ' ', p.lastname) AS patient_name,
       CONCAT(d.firstname, ' ', d.lastname) AS doctor_name,
       s.service AS service_name, 
       dept.name AS department_name
FROM appointment a
LEFT JOIN patient p ON a.patient_id = p.id
LEFT JOIN doctor d ON a.doctor_id = d.employee_id
LEFT JOIN services s ON a.service_id = s.id
LEFT JOIN departments dept ON a.department_id = dept.id
WHERE a.is_archive = 0 AND a.doctor_id = ?; -- Replace `1` with an actual doctor ID
"; // Filter by doctor ID

    // Execute the query
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$doctor_id]);

    // Fetch all appointments for the doctor
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare data for FullCalendar
    $calendar_events = [];

    foreach ($appointments as $appointment) {
        $calendar_events[] = [
            'id' => $appointment['id'],
            'title' => $appointment['service_name'], // Service and patient name as title
            'start' => $appointment['appointment_date'] . 'T' . $appointment['appointment_time'],
            'end' => $appointment['appointment_date'] . 'T' . date('H:i:s', strtotime($appointment['appointment_time']) + 3600),
            'extendedProps' => [
                'service_name' => $appointment['service_name'],
                'patient_name' => $appointment['patient_name'],
                'doctor_name' => $appointment['doctor_name'],
                'status' => $appointment['status'],
                'appointment_time' => $appointment['appointment_time'],
                'department_name' => $appointment['department_name']
            ]
        ];
    }

    // Return JSON-encoded data
    echo json_encode($calendar_events);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
