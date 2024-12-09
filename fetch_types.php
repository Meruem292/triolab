<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['category'] ?? '';

    try {
        $stmt = $pdo->prepare("SELECT DISTINCT type FROM services WHERE category = :category AND is_archive = 0");
        $stmt->execute([':category' => $category]);
        $types = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($types);
    } catch (Exception $e) {
        echo json_encode([]);
    }
}
