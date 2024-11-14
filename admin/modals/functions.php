<?php
include './db.php';

global $pdo;

function getAppointmentSlots(){
    
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM appointment_slots WHERE is_archive = 0');
    $stmt->execute();
    return $stmt->fetchAll();
}

function getServices(){
    
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM services  WHERE is_archive = 0');
    $stmt->execute();
    return $stmt->fetchAll();
}

function getDoctors(){
    
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM doctor WHERE is_archive = 0');
    $stmt->execute();
    return $stmt->fetchAll();
}

function getDoctorsByDeparment(){
    
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM doctor WHERE department =is_archive = 0');
    $stmt->execute();
    return $stmt->fetchAll();
}

function getPatients(){
    
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM patient');
    $stmt->execute();
    return $stmt->fetchAll();
}
?>