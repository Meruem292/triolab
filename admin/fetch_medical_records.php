<?php 
include('db.php');

if (isset($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id'];

    // Prepare the query to fetch medical records
    $stmt = $pdo->prepare("SELECT * FROM medical_records WHERE patient_id = ? ORDER BY record_date DESC");
    $stmt->execute([$patient_id]);

    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare the response
    if ($records) {
        echo json_encode(['records' => $records]);
    } else {
        echo json_encode(['records' => []]);
    }
} else {
    echo json_encode(['error' => 'No patient ID provided']);
}

?>