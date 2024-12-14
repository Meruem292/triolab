<?php
header('Content-Type: application/json');
require 'db.php'; // Add your database connection file here

$timeline = $_GET['timeline'] ?? 'daily'; // Default to 'daily'
$labels = [];
$values = [];
$total = 0;

// Default time condition
$timeCondition = "DATE(`appointment_date`) = CURDATE()";

// Adjust based on the timeline parameter
if ($timeline === 'weekly') {
    $timeCondition = "YEARWEEK(`appointment_date`, 1) = YEARWEEK(CURDATE(), 1)";
} elseif ($timeline === 'monthly') {
    $timeCondition = "MONTH(`appointment_date`) = MONTH(CURDATE()) AND YEAR(`appointment_date`) = YEAR(CURDATE())";
} elseif ($timeline === 'yearly') {
    $timeCondition = "YEAR(`appointment_date`) = YEAR(CURDATE())";
}

// SQL query to fetch service appointment counts
$sql = "
     SELECT 
        s.service AS service_name, 
        COUNT(a.id) AS appointment_count
    FROM 
        services s
    LEFT JOIN 
        appointment a ON s.id = a.service_id
    WHERE 
        $timeCondition
    GROUP BY 
        s.id
    ORDER BY 
        appointment_count DESC
";

try {
    // Prepare and execute the SQL query
    $stmt = $pdo->prepare($sql); // Use the PDO instance to prepare the statement
    $stmt->execute(); // Execute the query
    
    // Fetch the results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($results) {
        foreach ($results as $row) {
            $labels[] = $row['service_name'];
            $values[] = (int)$row['appointment_count'];
        }
        $total = array_sum($values);
    }

    echo json_encode([
        'labels' => $labels,
        'values' => $values,
        'total' => $total
    ]);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
