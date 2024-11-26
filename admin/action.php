<?php
include "db.php";
include "functions.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_payment'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    try {
        // Start a transaction
        $pdo->beginTransaction();

        // Update the status in the payment_receipts table
        $query = $pdo->prepare("UPDATE payment_receipts SET status = :status WHERE id = :id");
        $query->execute(['status' => $status, 'id' => $id]);

        // Retrieve the appointment_id related to the payment
        $query1 = $pdo->prepare("SELECT appointment_id FROM payment_receipts WHERE id = :id");
        $query1->execute(['id' => $id]);
        $result = $query1->fetch(PDO::FETCH_ASSOC);

        if ($result && isset($result['appointment_id'])) {
            $appointmentId = $result['appointment_id'];

            // Update the 'paid' status in the appointment table
            $query2 = $pdo->prepare("UPDATE appointment SET paid = :status WHERE id = :appointment_id");
            $query2->execute(['status' => $status, 'appointment_id' => $appointmentId]);

            // Commit the transaction
            $pdo->commit();

            // Log the successful action
            logAction($pdo, 'update payment status', 'Payment status updated to ' . $status . ' for payment ID: ' . $id);

            $_SESSION['message'] = "Status updated successfully.";
            $_SESSION['status'] = "success"; // Success icon for SweetAlert
        } else {
            throw new Exception("Failed to retrieve the related appointment.");
        }
    } catch (Exception $e) {
        // Roll back the transaction on error
        $pdo->rollBack();

        // Log the error action
        logAction($pdo, 'update payment status error', 'Failed to update payment status for payment ID: ' . $id . '. Error: ' . $e->getMessage());

        $_SESSION['message'] = "Failed to update status: " . $e->getMessage();
        $_SESSION['status'] = "error"; // Error icon for SweetAlert
    }

    header("Location: payments.php");
    exit;
}
