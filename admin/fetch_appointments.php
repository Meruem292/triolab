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

try {
    // SQL query to fetch detailed appointment data
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
        WHERE a.is_archive = 0";

    // Execute the query
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Fetch all appointments
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare data for FullCalendar
    $calendar_events = [];

    foreach ($appointments as $appointment) {
        // Determine if the appointment is expired
        $appointmentDateTime = new DateTime($appointment['appointment_date'] . ' ' . $appointment['appointment_time']);
        $now = new DateTime();

        // Determine event color based on status and expiration
        $eventColor = 'secondary'; // Default color for "Cancelled"

        if ($appointment['status'] === 'Completed') {
            $eventColor = 'green'; // Success color for completed appointments
        } elseif ($appointment['status'] === 'Pending') {
            $eventColor = $appointmentDateTime < $now ? 'red' : 'blue'; // Danger if expired, primary if not expired
        }

        $calendar_events[] = [
            'id' => $appointment['id'],
            'title' => $appointment['service_name'] . " (" . $appointment['department_name'] . ")",
            'start' => $appointment['appointment_date'] . 'T' . $appointment['appointment_time'],
            'end' => $appointment['appointment_date'] . 'T' . date('H:i:s', strtotime($appointment['appointment_time']) + 3600),
            'color' => $eventColor,
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
?>
