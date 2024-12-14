<?php
// Assuming you are using PDO for database connection
include 'db.php';

$data = json_decode(file_get_contents("php://input"));

$appId = $data->appointmentId;
$patientName = $data->patientName;
$appointmentDate = $data->appointmentDate;
$services = $data->services;

// Update main appointment status (if needed)
$stmt = $pdo->prepare("UPDATE appointment SET status = :status, paid = :paid, medical = :medical, appointment_date = :appointmentDate WHERE app_id = :appId");
$stmt->execute([
    'status' => '', // Change this to whatever status you'd like to set
    'paid' => '', // Change this to whatever status you'd like to set
    'medical' => '', // Change this to whatever status you'd like to set
    'appointmentDate' => $appointmentDate,
    'appId' => $appId
]);

// Update each service status based on service ID
foreach ($services as $service) {
    $stmt = $pdo->prepare("UPDATE appointment SET status = :status,  paid = :paid, medical = :medical WHERE id = :serviceId");
    $stmt->execute([
        'status' => $service->status,
        'paid' => $service->paid,
        'medical' => $service->medical,
        'serviceId' => $service->serviceId
    ]);
}

echo json_encode(['success' => true, 'message' => 'Appointment updated successfully!']);
?>
