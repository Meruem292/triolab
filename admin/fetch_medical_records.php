<?php
// Include database connection (ensure you have the PDO connection setup correctly)
include 'db.php';

header('Content-Type: application/json');

// Check if patient_id is passed via GET
if (isset($_GET['patient_id'])) {
    $patient_id = intval($_GET['patient_id']);  // Sanitize input

    // Prepare and execute the query to fetch medical records for the given patient_id
    $query = "SELECT * FROM medical_records WHERE patient_id = :patient_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':patient_id', $patient_id, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch all the records for the patient
    if ($stmt->rowCount() > 0) {
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['records' => $records]);
    } else {
        echo json_encode(['records' => []]);
    }
} else {
    echo json_encode(['error' => 'No patient ID provided']);
}
?>
