<?php
require 'db.php';

if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $table = $_POST['table'];

    $stmt = $pdo->prepare("DELETE FROM $table WHERE id = ?");
    if ($stmt->execute([$id])) {
        $_SESSION['message'] = "Item deleted successfully!";
        $_SESSION['status'] = "success";
    } else {
        $_SESSION['message'] = "Error deleting item.";
        $_SESSION['status'] = "error";
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}
?>