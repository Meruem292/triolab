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

function getDepartments(){
    
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM departments WHERE is_archive = 0');
    $stmt->execute();
    return $stmt->fetchAll();
}

function getPatients(){
    
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
?>