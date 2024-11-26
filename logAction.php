<?php
include "db.php";
function logAction($pdo, $action, $description)
{
    $stmt = $pdo->prepare("INSERT INTO logs (action, user, details) VALUES (?, ?, ?)");
    $stmt->execute([$action, $_SESSION['user_id'], $description]);
}

?>