<?php
include "db.php";
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_payment'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    // Update status in the database
    $query = $pdo->prepare("UPDATE payment_receipts SET status = :status WHERE id = :id");
    if ($query->execute(['status' => $status, 'id' => $id])) {
        $_SESSION['message'] = "Status updated successfully.";
        $_SESSION['status'] = "success"; // Success icon for SweetAlert
        

    } else {
        $_SESSION['message'] = "Failed to update status.";
        $_SESSION['status'] = "error"; // Error icon for SweetAlert
    }
    header("Location: payments.php");
}
?>