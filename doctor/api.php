<?php 
include "../db.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    // Get the form data
    $employee_id = $_SESSION['user_id']; // Assuming the employee_id is stored in the session
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Password is optional
    $profile_img = $_FILES['profile_img']['name']; // Profile image
    $profile_img_tmp = $_FILES['profile_img']['tmp_name'];

    // Check if password is provided
    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_DEFAULT); // Hash the password before saving
        $update_password = ", password = :password"; // Update password field
    } else {
        $update_password = ""; // Don't update password if empty
    }

    // Handle profile image upload
    if (!empty($profile_img)) {
        // Set the target directory for the profile image
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($profile_img);
        // Check if the target directory exists, if not, create it
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Move the uploaded file to the target directory
        if (move_uploaded_file($profile_img_tmp, $target_file)) {
            $update_profile_img = ", profile_img = :profile_img"; // Update profile image field
        } else {
            $update_profile_img = ""; // Don't update profile image if file upload failed
        }
    } else {
        $update_profile_img = ""; // Don't update profile image if no file is uploaded
    }

    try {
        // Update query
        $query = "UPDATE doctor SET 
                    firstname = :firstname,
                    lastname = :lastname,
                    username = :username,
                    email = :email
                    $update_password
                    $update_profile_img
                    WHERE employee_id = :employee_id";

        // Prepare statement
        $stmt = $pdo->prepare($query);

        // Bind parameters
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);

        if (!empty($password)) {
            $stmt->bindParam(':password', $password);
        }

        if (!empty($profile_img)) {
            $stmt->bindParam(':profile_img', $target_file);
        }

        $stmt->bindParam(':employee_id', $employee_id);

        // Execute the statement
        if ($stmt->execute()) {
            // Set success message
            $_SESSION['message'] = "Profile updated successfully!";
            $_SESSION['status'] = "success";
        } else {
            // Set error message
            $_SESSION['message'] = "Failed to update profile.";
            $_SESSION['status'] = "error";
        }
    } catch (Exception $e) {
        // Log error and set failure message
        error_log("Error updating profile: " . $e->getMessage());
        $_SESSION['message'] = "An error occurred while updating the profile.";
        $_SESSION['status'] = "error";
    }

    // Redirect to settings page
    header("Location: settings.php");
    exit();
}
?>
