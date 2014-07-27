<?php

session_start();

$user_id = $_SESSION['user_id'];
$firstname = $_SESSION['first_name'];
$lastname = $_SESSION['last_name'];



require ('pdf/fpdf.php');
$pdf = new FPDF( );
$pdf->AddPage();
// Set font size
$pdf->SetFontSize(10);
// Select our font family
$pdf->SetFont('Helvetica', '');
$pdf->Cell(50,10,'This is to certify that', 0, 0, 'L', FALSE);
$pdf->Cell(20,10,$firstname, 0, 0, 'L', FALSE);
$pdf->Cell(20,10,$lastname, 0, 0, 'L', FALSE);
$pdf->Cell(30,10,'(Roll -', 0, 0, 'L', FALSE);
$pdf->Cell(35,10,$user_id, 0, 0, 'L', FALSE);
$pdf->Cell(50,10,')is granted a No Dues.', 0, 1, 'L', FALSE);

$pdf->Output('nodues.pdf', 'I');
?>