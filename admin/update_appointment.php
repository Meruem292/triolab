<?php
// Get the JSON data sent from the client
$data = json_decode(file_get_contents('php://input'), true);

// Extract individual data points from the request
$appId = $data['appId'];
$patientName = $data['patientName'];
$appointmentDate = $data['appointmentDate'];
$status = $data['status'];
$totalCost = $data['totalCost'];
$services = $data['services']; // Array of service IDs to be updated

// Connect to the database (PDO connection)
include('db.php'); // Assuming you have a db_conn.php file for the PDO connection

try {
    // Start a transaction to ensure atomicity
    $pdo->beginTransaction();

    // Step 1: Update the `appointment` table
    $stmt = $pdo->prepare("UPDATE appointment SET appointment_date = ?, status = ?, total_cost = ? WHERE id = ?");
    $stmt->execute([$appointmentDate, $status, $totalCost, $appId]);

    // Step 2: Update or insert the services (depending on your needs)
    // Assuming you're updating the services list for this appointment
    // You might need to delete old services and insert new ones or update existing ones
    // This is just an example, adjust as necessary for your table structure
    $stmt = $pdo->prepare("DELETE FROM appointment_services WHERE appointment_id = ?");
    $stmt->execute([$appId]);

    // Insert updated services
    foreach ($services as $serviceId) {
        $stmt = $pdo->prepare("INSERT INTO appointment_services (appointment_id, service_id) VALUES (?, ?)");
        $stmt->execute([$appId, $serviceId]);
    }

    // Commit the transaction if all steps were successful
    $pdo->commit();

    // Return success response
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Rollback the transaction in case of error
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
