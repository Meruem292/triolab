<?php
require 'db.php';

if (isset($_POST['unarchive'])) {
    $id = $_POST['id'];
    $table = $_POST['table'];

    $stmt = $pdo->prepare("UPDATE $table SET is_archive = 0 WHERE id = ?");
    if ($stmt->execute([$id])) {
        $_SESSION['message'] = "Item unarchived successfully!";
        $_SESSION['status'] = "success";
    } else {
        $_SESSION['message'] = "Error unarchiving item.";
        $_SESSION['status'] = "error";
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}
?>