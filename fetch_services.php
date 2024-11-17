<?php

require "db.php";

if(isset($_POST['searchValue'])) {
    // Sanitize search value
    $searchValue = trim($_POST['searchValue']);

    // Prepare SQL statement to fetch keywords
    $stmt = $pdo->prepare("SELECT * FROM services WHERE is_archive = 0 AND service LIKE CONCAT('%', :searchValue, '%')");
    $stmt->bindValue(':searchValue', $searchValue, PDO::PARAM_STR);
    
    // Execute SQL statement
    $stmt->execute();

    // Fetch results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return results as JSON
    echo json_encode($results);
} else {
    // If search value is not provided, return an empty array
    echo json_encode([]);
}

?>
