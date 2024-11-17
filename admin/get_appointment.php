<?php
include 'db.php';

if (isset($_GET['id'])) {
    $appointmentId = $_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM appointments WHERE id = ?');
    $stmt->execute([$appointmentId]);
    $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($appointment) {
        echo json_encode($appointment);
    } else {
        echo json_encode(['error' => 'Appointment not found']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>