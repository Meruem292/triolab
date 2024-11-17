<?php
session_start();
include 'db.php';

function logAction($pdo, $action, $description)
{
    $stmt = $pdo->prepare("INSERT INTO logs (action, user, details) VALUES (?, ?, ?)");
    $stmt->execute([$action, $_SESSION['user_username'], $description]);
}

// APPOINTMENT
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
    logAction($pdo, 'add appointment', 'Appointment added for patient ID: ' . $patient);
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
            logAction($pdo, 'add doctor', 'Doctor added with employee ID: ' . $employee_id);
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
        logAction($pdo, 'edit doctor', 'Doctor updated with employee ID: ' . $employee_id);
        $_SESSION['message'] = "Doctor updated successfully.";
        $_SESSION['status'] = "success";
    } else {
        logAction($pdo, 'edit doctor error', 'Error updating doctor with employee ID: ' . $employee_id);
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
        logAction($pdo, 'archive doctor', 'Doctor archived with employee ID: ' . $doctor_id);
        $_SESSION['message'] = "Doctor archived successfully.";
        $_SESSION['status'] = "success";
    } else {
        logAction($pdo, 'archive doctor error', 'Error archiving doctor with employee ID: ' . $doctor_id);
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
        logAction($pdo, 'add_patient_error', 'Patient with email ' . $email . ' already exists.');
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
            logAction($pdo, 'add patient', 'Patient added with email: ' . $email);
            $_SESSION['message'] = "Patient added successfully.";
            $_SESSION['status'] = "success";
        } else {
            logAction($pdo, 'add patient error', 'Error inserting patient with email: ' . $email);
            $_SESSION['message'] = "Error inserting patient.";
            $_SESSION['status'] = "error";
        }
    }
    header('location: ../patients.php');
}


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
        logAction($pdo, 'edit patient', 'Patient updated with ID: ' . $patientId);
        $_SESSION['message'] = "Patient updated successfully.";
        $_SESSION['status'] = "success";
        header('Location: ../patients.php');
        exit;
    } else {
        logAction($pdo, 'edit patient error', 'Error updating patient with ID: ' . $patientId);
        $_SESSION['message'] = "Error updating patient.";
        $_SESSION['status'] = "error";
    }
}

// MEDICAL RECORD

if (isset($_POST['submit_medical_record'])) {
    $patient_id = $_POST['patient_id'];  // Assuming you have patient_id from your form
    $diagnosis = $_POST['diagnosis'];
    $treatment = $_POST['treatment'];
    $record_date = $_POST['record_date'];

    // Check if the patient exists by their 'id'
    $checkPatient = $pdo->prepare("SELECT COUNT(*) FROM patient WHERE id = ?");
    $checkPatient->execute([$patient_id]);
    $patientExists = $checkPatient->fetchColumn();

    if ($patientExists == 0) {
        logAction($pdo, 'submit medical record error', 'Patient does not exist with ID: ' . $patient_id);
        $_SESSION['message'] = "Patient does not exist!";
        $_SESSION['status'] = "error";
        header("Location: ../medical-records.php");
        exit;
    }

    // Insert the medical record with the correct patient_id (which is 'id' from the patient table)
    try {
        $stmt = $pdo->prepare("INSERT INTO medical_records (patient_id, record_date, diagnosis, treatment) VALUES (?, ?, ?, ?)");
        $stmt->execute([$patient_id, $record_date, $diagnosis, $treatment]);

        logAction($pdo, 'Submit medical record', 'Medical record added for patient ID: ' . $patient_id);
        $_SESSION['message'] = "Medical record added successfully!";
        $_SESSION['status'] = "success";
        header("Location: ../medical-records.php");
    } catch (PDOException $e) {
        logAction($pdo, 'Submit medical record error', 'Error adding medical record for patient ID: ' . $patient_id . '. Error: ' . $e->getMessage());
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['status'] = "error";
        header("Location: ../medical-records.php");
    }
}

if (isset($_POST['update_medical_record'])) {
    $medical_id = $_POST['medical_id'];
    $diagnosis = $_POST['diagnosis'];
    $treatment = $_POST['treatment'];
    $record_date = $_POST['record_date'];

    // Update medical record
    $query = "UPDATE medical_records SET diagnosis = :diagnosis, treatment = :treatment, record_date = :record_date 
              WHERE id = :medical_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':diagnosis' => $diagnosis,
        ':treatment' => $treatment,
        ':record_date' => $record_date,
        ':medical_id' => $medical_id
    ]);

    $_SESSION['message'] = "Medical record updated successfully!";
    $_SESSION['status'] = "success";
    header("Location: ../medical-records.php");
}


if (isset($_POST['update_medical_record'])) {
    $medical_id = $_POST['medical_id'];
    $diagnosis = $_POST['diagnosis'];
    $treatment = $_POST['treatment'];
    $record_date = $_POST['record_date'];

    // Update medical record
    $query = "UPDATE medical_records SET diagnosis = :diagnosis, treatment = :treatment, record_date = :record_date 
              WHERE id = :medical_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':diagnosis' => $diagnosis,
        ':treatment' => $treatment,
        ':record_date' => $record_date,
        ':medical_id' => $medical_id
    ]);

    logAction($pdo, 'Update medical record', 'Medical record updated with ID: ' . $medical_id);
    $_SESSION['message'] = "Medical record updated successfully!";
    $_SESSION['status'] = "success";
    header("Location: ../medical-records.php");
}

if (isset($_POST['upload_payment_method'])) {
    $method_id = $_POST['method_id'];  // Payment method ID
    $image = $_FILES['image_path'];  // The uploaded image

    // Prepare the query to update the image and updated_at timestamp
    $query = "UPDATE payment_mode SET updated_at = NOW()";

    // If an image is uploaded, handle the file update
    if ($image['error'] == 0) {
        $target_dir = "uploads/payment_methods/";
        $target_file = $target_dir . basename($image['name']);
        if (move_uploaded_file($image['tmp_name'], $target_file)) {
            // Add image path to the query if new image is uploaded
            $query .= ", image_path = ?";
            $params[] = $target_file;
        } else {
            // Handle error if image upload failed
            logAction($pdo, 'Upload payment method_error', 'Image upload failed for payment method ID: ' . $method_id);
            $_SESSION['message'] = "Image upload failed.";
            $_SESSION['status'] = "error";
            header("Location: ../payments.php");
            exit;
        }
    } else {
        // If no new image is uploaded, retain the existing image
        $existing_image = $_POST['existing_image']; // The current image path
        $params[] = $existing_image;
    }

    // Finalize the query with the WHERE clause
    $query .= " WHERE id = ?";
    $params[] = $method_id;

    // Prepare and execute the query
    $stmt = $pdo->prepare($query);
    if ($stmt->execute($params)) {
        logAction($pdo, 'Upload payment method', 'Payment method updated with ID: ' . $method_id);
        $_SESSION['message'] = "Payment method updated successfully!";
        $_SESSION['status'] = "success";
    } else {
        logAction($pdo, 'Upload payment method error', 'Failed to update payment method with ID: ' . $method_id);
        $_SESSION['message'] = "Failed to update payment method.";
        $_SESSION['status'] = "error";
    }

    header("Location: ../payments.php");
}
