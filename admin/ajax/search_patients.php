<?php
// Include database connection (make sure you use PDO for better security)
include('./db.php');

// Get the search query from the GET request
$searchQuery = isset($_GET['query']) ? $_GET['query'] : '';

// Prepare and execute the query to search patients
if ($searchQuery) {
    $stmt = $pdo->prepare("SELECT id, firstname, lastname FROM patients WHERE firstname LIKE :query OR lastname LIKE :query");
    $stmt->execute(['query' => '%' . $searchQuery . '%']);
    
    // Fetch the results
    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return the results as JSON
    echo json_encode($patients);
}
?>
