<?php
include './db.php';

global $pdo;

function getAppointmentSlots()
{

    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM appointment_slots WHERE is_archive = 0');
    $stmt->execute();
    return $stmt->fetchAll();
}

function getServices()
{

    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM services  WHERE is_archive = 0');
    $stmt->execute();
    return $stmt->fetchAll();
}

function getDoctors()
{

    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM doctor WHERE is_archive = 0');
    $stmt->execute();
    return $stmt->fetchAll();
}

function getDepartments()
{

    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM departments WHERE is_archive = 0');
    $stmt->execute();
    return $stmt->fetchAll();
}

function getPatients()
{

    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM patient');
    $stmt->execute();
    return $stmt->fetchAll();
}

function generateEmployeeID($prefix)
{
    // Generate a random 4-digit number
    $randomNumber = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);

    // Concatenate the prefix and the random 4-digit number
    return $prefix . $randomNumber;
}

function getCompleteDetailsByAppId($app_id)
{
    $query = "SELECT 
    a.id AS appointment_id,
    a.app_id,
    a.service_id,
    a.patient_id,
    a.appointment_date,
    a.appointment_slot_id,
    a.appointment_time,
    a.doctor_id AS appointment_doctor_id,
    a.department_id AS appointment_department_id,
    a.selectedPayment,
    a.medical,
    a.status AS appointment_status,
    a.paid,
    a.date_added AS appointment_date_added,
    a.is_archive AS appointment_is_archive,

    aslot.id AS appointment_slot_id,
    aslot.schedule AS appointment_slot_schedule,
    aslot.date AS appointment_slot_date,
    aslot.slot AS appointment_slot_slot,
    aslot.is_archive AS appointment_slot_is_archive,

    d.id AS department_id,
    d.name AS department_name,
    d.date_added AS department_date_added,
    d.is_archive AS department_is_archive,

    doc.employee_id AS doctor_employee_id,
    doc.firstname AS doctor_firstname,
    doc.lastname AS doctor_lastname,
    doc.username AS doctor_username,
    doc.email AS doctor_email,
    doc.profile_img AS doctor_profile_img,
    doc.department_id AS doctor_department_id,
    doc.date_added AS doctor_date_added,
    doc.is_archive AS doctor_is_archive,

    l.id AS log_id,
    l.action AS log_action,
    l.user AS log_user,
    l.timestamp AS log_timestamp,
    l.details AS log_details,
    l.is_archive AS log_is_archive,

    mr.id AS medical_record_id,
    mr.patient_id AS medical_record_patient_id,
    mr.appointment_id AS medical_record_appointment_id,
    mr.diagnosis,
    mr.treatment,
    mr.prescription,
    mr.content,
    mr.status AS medical_record_status,
    mr.record_date,
    mr.created_at AS medical_record_created_at,
    mr.updated_at AS medical_record_updated_at,

    p.id AS patient_id,
    p.firstname AS patient_firstname,
    p.lastname AS patient_lastname,
    p.age AS patient_age,
    p.sex AS patient_sex,
    p.email AS patient_email,
    p.dob AS patient_dob,
    p.birthplace AS patient_birthplace,
    p.contact AS patient_contact,
    p.province AS patient_province,
    p.city AS patient_city,
    p.barangay AS patient_barangay,
    p.street AS patient_street,
    p.date_added AS patient_date_added,

    pm.id AS payment_mode_id,
    pm.method AS payment_method,
    pm.image_path AS payment_image_path,
    pm.updated_at AS payment_updated_at,

    pr.id AS payment_receipt_id,
    pr.appointment_id AS payment_receipt_appointment_id,
    pr.payment_receipt_path,
    pr.date AS payment_receipt_date,
    pr.payment_mode_id AS payment_receipt_payment_mode_id,
    pr.amount AS payment_receipt_amount,
    pr.status AS payment_receipt_status,

    s.id AS service_id,
    s.category AS service_category,
    s.type AS service_type,
    s.service AS service_name,
    s.department_id AS service_department_id,
    s.cost AS service_cost,
    s.date_added AS service_date_added,
    s.is_archive AS service_is_archive,

    pf.id AS patient_file_id,
    pf.patient_id AS patient_file_patient_id,
    pf.directory AS patient_file_directory,
    pf.file_name AS patient_file_name,
    pf.uploaded_at AS patient_file_uploaded_at
FROM appointment a
LEFT JOIN appointment_slots aslot ON a.appointment_slot_id = aslot.id
LEFT JOIN departments d ON a.department_id = d.id
LEFT JOIN doctor doc ON a.doctor_id = doc.employee_id
LEFT JOIN logs l ON a.app_id = l.id
LEFT JOIN medical_records mr ON a.app_id = mr.appointment_id
LEFT JOIN patient p ON a.patient_id = p.id
LEFT JOIN payment_mode pm ON a.selectedPayment = pm.id
LEFT JOIN payment_receipts pr ON a.app_id = pr.appointment_id
LEFT JOIN services s ON a.service_id = s.id
LEFT JOIN patient_files pf ON p.id = pf.patient_id;
WHERE a.app_id = :app_id";
    global $pdo;
    $stmt = $pdo->prepare($query);
    $stmt->execute(['app_id' => $app_id]);
    return $stmt->fetch();
}
