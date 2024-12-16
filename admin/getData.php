<?php
header('Content-Type: application/json');

// Include the database connection file
require 'db.php';

try {
    // Get the timeline parameter and sanitize it
    $timeline = filter_input(INPUT_GET, 'timeline', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? 'daily';

    // Base query for counting appointments grouped by services
    $query = "
        SELECT 
            s.service AS service_name, 
            COUNT(a.id) AS appointment_count
        FROM 
            appointment a
        INNER JOIN 
            services s ON a.service_id = s.id
        WHERE 
            a.is_archive = 0 
            AND s.is_archive = 0
    ";

    // Adjust query based on the timeline
    if ($timeline === 'daily') {
        $query .= " AND DATE(a.date_added) = CURDATE()";
    } elseif ($timeline === 'weekly') {
        $query .= " AND a.date_added >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
    } elseif ($timeline === 'monthly') {
        $query .= " AND MONTH(a.date_added) = MONTH(CURDATE()) AND YEAR(a.date_added) = YEAR(CURDATE())";
    } elseif ($timeline === 'yearly') {
        $query .= " AND YEAR(a.date_added) = YEAR(CURDATE())";
    }
    

    $query .= " GROUP BY s.service ORDER BY appointment_count DESC";

    // Execute the query
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare chart data
    $labels = array_column($data, 'service_name');
    $values = array_column($data, 'appointment_count');
    $total = array_sum($values);

    // Respond with JSON
    echo json_encode([
        'labels' => $labels,
        'values' => $values,
        'total' => $total
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database query failed: ' . $e->getMessage()]);
}
