<?php
session_start();
include 'db.php';

// APPORTIONMENT
if (isset($_POST['add_appointment'])) {
    $patient = $_POST['patient'];
    $service = $_POST['service'];
    $department = $_POST['department'];
    $doctor = $_POST['doctor'];
    $date = $_POST['date'];
    $schedule = $_POST['schedule'];
    $time = $_POST['time'];
    $status = 'pending';
    $stmt = $pdo->prepare('INSERT INTO appointment (patient_id, service_id, department_id, doctor_id, appointment_date, appointment_slot_id, appointment_time, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$patient, $service, $department, $doctor, $date, $schedule, $time, $status]);
    $_SESSION['success'] = 'Appointment added successfully';
    header('location: ../appointment.php');
    exit();
}

// DOCTOR
if (isset($_POST['add_doctor'])) {
    $employee_id = $_POST['employee_id'];
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $department_id = $_POST['department_id']; // Using department_id now
    $password = $_POST['username']; // No hashing as per requirement

    $checkQuery = $pdo->prepare("SELECT * FROM doctor WHERE email = :email OR username = :username");
    $checkQuery->bindParam(':email', $email);
    $checkQuery->bindParam(':username', $username);
    $checkQuery->execute();

    if ($checkQuery->rowCount() > 0) {
        // Entry already exists
        $_SESSION['message'] = "Doctor already exists in the database.";
        $_SESSION['status'] = "warning";
    } else {
        // Insert the new doctor information into the database
        $insertQuery = $pdo->prepare("
            INSERT INTO doctor (employee_id, firstname, lastname, username, email, password, department_id) 
            VALUES (:employee_id, :firstname, :lastname, :username, :email, :password, :department_id)
        ");
        $insertQuery->bindParam(':employee_id', $employee_id);
        $insertQuery->bindParam(':firstname', $firstname);
        $insertQuery->bindParam(':lastname', $lastname);
        $insertQuery->bindParam(':username', $username);
        $insertQuery->bindParam(':email', $email);
        $insertQuery->bindParam(':password', $password);
        $insertQuery->bindParam(':department_id', $department_id);

        if ($insertQuery->execute()) {
            $_SESSION['message'] = "Doctor added successfully.";
            $_SESSION['status'] = "success";
        } else {
            $_SESSION['message'] = "Error inserting doctor.";
            $_SESSION['status'] = "error";
        }
    }
    header('location: ../doctors.php');
}

if (isset($_POST['edit_doctor'])) {
    // Prepare data for update
    $employee_id = $_POST['employee_id'];
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $department_id = $_POST['department_id']; // Using department_id now

    // Update the doctor information in the database
    $updateQuery = $pdo->prepare("
        UPDATE doctor 
        SET firstname = :firstname, lastname = :lastname, email = :email, username = :username, department_id = :department_id 
        WHERE employee_id = :id
    ");
    $updateQuery->bindParam(':firstname', $firstname);
    $updateQuery->bindParam(':lastname', $lastname);
    $updateQuery->bindParam(':email', $email);
    $updateQuery->bindParam(':username', $username);
    $updateQuery->bindParam(':department_id', $department_id);
    $updateQuery->bindParam(':id', $employee_id);

    if ($updateQuery->execute()) {
        $_SESSION['message'] = "Doctor updated successfully.";
        $_SESSION['status'] = "success";
    } else {
        $_SESSION['message'] = "Error updating doctor.";
        $_SESSION['status'] = "error";
    }

    header('location: ../doctors.php');
}

if (isset($_POST['archive_doctor'])) {
    $doctor_id = $_POST['doctorIdDelete'];
    $archive = 1;

    // Archive the doctor in the database
    $archiveQuery = $pdo->prepare("UPDATE doctor SET is_archive = :archive WHERE employee_id = :id");
    $archiveQuery->bindParam(':archive', $archive);
    $archiveQuery->bindParam(':id', $doctor_id);

    if ($archiveQuery->execute()) {
        $_SESSION['message'] = "Doctor archived successfully.";
        $_SESSION['status'] = "success";
    } else {
        $_SESSION['message'] = "Error archiving doctor.";
        $_SESSION['status'] = "error";
    }

    header('location: ../doctors.php');
}

// PATIENT

if (isset($_POST['add_patient'])) {
    // Get form data
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $email = htmlspecialchars($_POST['email']);
    $contact = htmlspecialchars($_POST['contact']);
    $dob = htmlspecialchars($_POST['dob']);
    $province = htmlspecialchars($_POST['province']);
    $city = htmlspecialchars($_POST['city']);
    $barangay = htmlspecialchars($_POST['barangay']);
    $street = htmlspecialchars($_POST['street']);

    // Check if the email already exists in the database
    $checkQuery = $pdo->prepare("SELECT * FROM patient WHERE email = :email");
    $checkQuery->bindParam(':email', $email);
    $checkQuery->execute();

    if ($checkQuery->rowCount() > 0) {
        // Entry already exists
        $_SESSION['message'] = "Patient with this email already exists.";
        $_SESSION['status'] = "warning";
    } else {
        // Insert the new patient information into the database
        $insertQuery = $pdo->prepare("
            INSERT INTO patient (firstname, lastname, email, contact, dob, province, city, barangay, street) 
            VALUES (:firstname, :lastname, :email, :contact, :dob, :province, :city, :barangay, :street)
        ");
        $insertQuery->bindParam(':firstname', $firstname);
        $insertQuery->bindParam(':lastname', $lastname);
        $insertQuery->bindParam(':email', $email);
        $insertQuery->bindParam(':contact', $contact);
        $insertQuery->bindParam(':dob', $dob);
        $insertQuery->bindParam(':province', $province);
        $insertQuery->bindParam(':city', $city);
        $insertQuery->bindParam(':barangay', $barangay);
        $insertQuery->bindParam(':street', $street);

        if ($insertQuery->execute()) {
            $_SESSION['message'] = "Patient added successfully.";
            $_SESSION['status'] = "success";
        } else {
            $_SESSION['message'] = "Error inserting patient.";
            $_SESSION['status'] = "error";
        }
    }
    header('location: ../patients.php');
}

// Handle the form submission for updating the patient
// Update patient information
if (isset($_POST['edit_patient'])) {
    // Retrieve form values from POST request
    $patientId = $_POST['patientId'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $dob = $_POST['dob'];
    $province = $_POST['province'];  // Directly retrieve province
    $city = $_POST['city'];          // Directly retrieve city
    $barangay = $_POST['barangay'];  // Directly retrieve barangay
    $street = $_POST['street'];      // Directly retrieve street

    // Prepare and execute the update query
    $updateQuery = $pdo->prepare("
        UPDATE patient SET
            firstname = :firstname,
            lastname = :lastname,
            email = :email,
            contact = :contact,
            dob = :dob,
            province = :province,
            city = :city,
            barangay = :barangay,
            street = :street
        WHERE id = :patientId
    ");

    // Bind the parameters
    $updateQuery->bindParam(':firstname', $firstname);
    $updateQuery->bindParam(':lastname', $lastname);
    $updateQuery->bindParam(':email', $email);
    $updateQuery->bindParam(':contact', $contact);
    $updateQuery->bindParam(':dob', $dob);
    $updateQuery->bindParam(':province', $province);
    $updateQuery->bindParam(':city', $city);
    $updateQuery->bindParam(':barangay', $barangay);
    $updateQuery->bindParam(':street', $street);
    $updateQuery->bindParam(':patientId', $patientId);

    // Execute the query and handle success/error
    if ($updateQuery->execute()) {
        $_SESSION['message'] = "Patient updated successfully.";
        $_SESSION['status'] = "success";
        header('Location: ../patients.php');
        exit;
    } else {
        $_SESSION['message'] = "Error updating patient.";
        $_SESSION['status'] = "error";
    }
}

