<?php
session_start();
// Include your database connection file
include('db.php'); // Adjust this to your actual connection file

// Include the function for unarchiving
include('table_function.php'); // Ensure unArchiveData is correctly defined in this file

if (isset($_POST['unarchive'])) {
    // Get the record ID from the form
    $id = $_POST['id'];
    $table = $_POST['table'];

    // Call the unarchiveData function to unarchive the record
    $result = unArchiveData($pdo, $table, $id);

    // Set session message and status based on result
    if ($result) {
        $_SESSION['message'] = "Record successfully unarchived.";
        $_SESSION['status'] = "success"; // Success icon
    } else {
        $_SESSION['message'] = "Failed to unarchive the record.";
        $_SESSION['status'] = "error"; // Error icon
    }

    // Redirect back to the previous page
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
?>
