<?php
session_start();
require('assets/fpdf186/fpdf.php');
// Retrieve data from URL
$appointmentId = $_GET['appointment_id'] ?? '';
$serviceName = urldecode($_GET['service'] ?? 'Unknown');
$appointmentDate = urldecode($_GET['date'] ?? 'Unknown');
$appointmentTime = urldecode($_GET['time'] ?? 'Unknown');
$appointmentPayment = urldecode($_GET['payment'] ?? 'Unknown');
$doctorName = urldecode($_GET['doctor'] ?? 'Unknown');
$appointmentNote = urldecode($_GET['note'] ?? 'None');
$appointmentPaid = urldecode($_GET['paid'] ?? 'No');

$email = $_SESSION['user_email'];
$fname = $_SESSION['user_firstname'];
$lname = $_SESSION['user_lastname'];
$phone = $_SESSION['user_phone'];

// Create a new PDF instance
$pdf = new FPDF();
$pdf->AddPage();

// Add the header
$pdf->Image('assets/images/triolab_header.png', 10, 10, 190); // Adjust path, x, y, and width
$pdf->Ln(40); // Move below the image

// Add a title below the header
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Appointment Receipt', 0, 1, 'C');
$pdf->Ln(10); // Space after the title

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(50, 10, 'Phone No.:', 0);
$pdf->Cell(100, 10, $appointmentId, 0, 1);

// Appointment Details
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(50, 10, 'Client Name:', 0);
$pdf->Cell(100, 10, $lname. ", " .$fname, 0, 1);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(50, 10, 'Email:', 0);
$pdf->Cell(100, 10, $email, 0, 1);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(50, 10, 'Phone No.:', 0);
$pdf->Cell(100, 10, $phone, 0, 1);



$pdf->Cell(50, 10, 'Service:', 0);
$pdf->Cell(100, 10, $serviceName, 0, 1);

$pdf->Cell(50, 10, 'Date & Time:', 0);
$pdf->Cell(100, 10, $appointmentDate . ' (' . $appointmentTime . ')', 0, 1);

$pdf->Cell(50, 10, 'Doctor:', 0);
$pdf->Cell(100, 10, $doctorName, 0, 1);

$pdf->Cell(50, 10, 'Payment Method:', 0);
$pdf->Cell(100, 10, $appointmentPayment, 0, 1);

$pdf->Cell(50, 10, 'Paid:', 0);
$pdf->Cell(100, 10, $appointmentPaid, 0, 1);

$pdf->Cell(50, 10, 'Note:', 0);
$pdf->MultiCell(100, 10, $appointmentNote, 0, 1);

// Output PDF
$pdf->Output('D', 'Appointment_Receipt_' . $appointmentId . '.pdf');
?>
