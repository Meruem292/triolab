<?php
require 'db.php';

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $stmt = $pdo->prepare("SELECT id, CONCAT(firstname, ' ', lastname) AS name FROM patient WHERE CONCAT(firstname, ' ', lastname) LIKE ? OR id LIKE ?");
    $stmt->execute(["%$query%", "%$query%"]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($users) > 0) {
        // Loop through the results and return them as list group items
        foreach ($users as $user) {
            echo '<a href="#" class="list-group-item list-group-item-action" onclick="selectUser(' . $user['id'] . ', \'' . htmlspecialchars($user['name']) . '\')">' . htmlspecialchars($user['name']) . ' (ID: ' . htmlspecialchars($user['id']) . ')</a>';
        }
    } else {
        // Display message when no results found
        echo '<div class="list-group-item">No results found</div>';
    }
}
?>
