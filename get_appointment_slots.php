<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id = $_POST['service_id'] ?? null; // Use service_id instead of service_type
    $start_date = $_POST['start_date'] ?? null;
    $end_date = $_POST['end_date'] ?? null;

    if (!$service_id || !$start_date || !$end_date) {
        echo json_encode([]); // Return empty array if required parameters are missing
        exit;
    }

    try {
        // Fetch appointment slots for the selected service
        $query = "
            SELECT 
                appointment_slots.date, 
                appointment_slots.slot 
            FROM 
                appointment_slots
            WHERE 
                appointment_slots.is_archive = 0
                AND appointment_slots.date BETWEEN :start_date AND :end_date
                AND appointment_slots.id IN (
                    SELECT appointment_slot_id 
                    FROM appointment 
                    WHERE service_id = :service_id
                )
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':service_id' => $service_id,
            ':start_date' => $start_date,
            ':end_date' => $end_date
        ]);

        $slots = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($slots);

    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
