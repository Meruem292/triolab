<?php
require 'db.php';

try {
    // Query to fetch appointments
    $stmt = $pdo->prepare("SELECT id, appointment_date, appointment_time, status FROM appointment WHERE is_archive = 0");
    $stmt->execute();
    $appointments = $stmt->fetchAll();

    // Transform data to fit the calendar script
    $events = array_map(function ($appointment) {
        return [
            'eventName' => 'Appointment ID: ' . $appointment['id'],
            'calendar' => $appointment['status'] === 'confirmed' ? 'Confirmed' : 'Pending',
            'color' => $appointment['status'] === 'confirmed' ? 'green' : 'red',
            'date' => $appointment['appointment_date'],
        ];
    }, $appointments);

    echo json_encode($events);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
