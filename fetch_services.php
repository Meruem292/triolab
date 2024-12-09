<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? '';

    try {
        $stmt = $pdo->prepare("SELECT id, service FROM services WHERE type = :type AND is_archive = 0");
        $stmt->execute([':type' => $type]);
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($services);
    } catch (Exception $e) {
        echo json_encode([]);
    }
}
