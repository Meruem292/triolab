<?php
// get_appointment_data.php
include 'db.php';

if (isset($_GET['app_id'])) {
    $app_id = $_GET['app_id'];

    $query = "
        SELECT
            appointment.id AS id,
            appointment.app_id,
            appointment.paid,
            patient.firstname AS patient_firstname,
            patient.lastname AS patient_lastname,
            services.service AS service_name,
            services.cost AS service_cost,
            doctor.employee_id AS doctor_id,
            doctor.firstname AS doctor_firstname,
            doctor.lastname AS doctor_lastname,
            appointment.appointment_time,
            appointment.appointment_date,
            appointment.status
        FROM appointment
        INNER JOIN patient ON appointment.patient_id = patient.id
        INNER JOIN services ON appointment.service_id = services.id
        LEFT JOIN doctor ON appointment.doctor_id = doctor.employee_id
        WHERE appointment.app_id = :app_id AND appointment.is_archive = 0
        ORDER BY appointment.date_added ASC;
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute([':app_id' => $app_id]);
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($appointments);
}
?>
