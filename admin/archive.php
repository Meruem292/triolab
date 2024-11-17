<?php
require 'db.php';

if (isset($_POST['archive'])) {
    $id = $_POST['id'];
    $table = $_POST['table'];

    $stmt = $pdo->prepare("UPDATE $table SET is_archive = 1 WHERE id = ?");
    if ($stmt->execute([$id])) {
        $_SESSION['message'] = "Item archived successfully!";
        $_SESSION['status'] = "success";
    } else {
        $_SESSION['message'] = "Error archiving item.";
        $_SESSION['status'] = "error";
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}
?>