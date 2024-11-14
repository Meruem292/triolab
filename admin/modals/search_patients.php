<?php
include './db.php'; // Make sure you include the database connection


// Get the search query from the GET request
$searchQuery = isset($_GET['query']) ? $_GET['query'] : '';

// Prepare the SQL query to search patients by their first or last name
if ($searchQuery) {
    $stmt = $pdo->prepare("SELECT id, firstname, lastname FROM patients WHERE firstname LIKE :query OR lastname LIKE :query");
    $stmt->execute(['query' => '%' . $searchQuery . '%']);
    
    // Fetch the results
    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // If no patients found, send a message
    if (count($patients) === 0) {
        echo json_encode(['message' => 'No patients found']);
    } else {
        // Return patients as a JSON response
        echo json_encode($patients);
    }
} else {
    echo json_encode([]);
}
?>
