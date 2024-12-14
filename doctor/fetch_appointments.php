<?php
// Include your database connection
include('db.php');
session_start();

header('Content-Type: application/json');

// Check if employee_id is set in the session
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not authenticated']);
    exit;
}

try {
    $doctor_id = $_SESSION['user_id']; // Retrieve the logged-in doctor's ID from the session

    // SQL query to fetch detailed appointment data for the logged-in doctor
    $sql = "
        SELECT 
    a.id, 
    a.appointment_date, 
    a.appointment_time, 
    a.status AS appointment_status, 
    CONCAT(p.firstname, ' ', p.lastname) AS patient_name, 
    CONCAT(d.firstname, ' ', d.lastname) AS doctor_name,
    s.service AS service_name, 
    dept.name AS department_name,
    mr.status AS medical_record_status
FROM 
    appointment a
LEFT JOIN 
    patient p ON a.patient_id = p.id
LEFT JOIN 
    doctor d ON a.doctor_id = d.employee_id
LEFT JOIN 
    services s ON a.service_id = s.id
LEFT JOIN 
    departments dept ON a.department_id = dept.id
LEFT JOIN 
    medical_records mr ON a.id = mr.appointment_id
WHERE 
    a.is_archive = 0 
    AND a.doctor_id = ?
    AND appointment_status != 'Cancelled'

    ; 
    ";

    // Execute the query
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$doctor_id]);

    // Fetch all appointments for the doctor
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare data for FullCalendar
    $calendar_events = [];

    if (!empty($appointments)) {
        foreach ($appointments as $appointment) {
            // Ensure appointment time is correctly formatted (in case of missing time)
            $start_time = $appointment['appointment_date'] . 'T' . $appointment['appointment_time'];
            $end_time = date('H:i:s', strtotime($appointment['appointment_time']) + 3600); // Assuming 1-hour duration
            $end_time = $appointment['appointment_date'] . 'T' . $end_time;

            // Add event data for FullCalendar
            $calendar_events[] = [
                'id' => $appointment['id'],
                'title' => $appointment['service_name'] . '(' . $appointment['appointment_time'] . ')', // Service and patient name as title
                'start' => $start_time,
                'end' => $end_time,
                'extendedProps' => [
                    'service_name' => $appointment['service_name'],
                    'patient_name' => $appointment['patient_name'],
                    'doctor_name' => $appointment['doctor_name'],
                    'status' => $appointment['medical_record_status'],
                    'appointment_time' => $appointment['appointment_time'],
                    'department_name' => $appointment['department_name']
                ]
            ];
        }
    } else {
        // If no appointments found
        $calendar_events = ['message' => 'No appointments found for this doctor.'];
    }

    // Return JSON-encoded data
    echo json_encode($calendar_events);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
