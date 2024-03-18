<?php

require('assets/fpdf/fpdf.php');
require('assets/fpdf/makefont/makefont.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "dithetoc_roia";
$password = "rolanga4";
$dbname = "dithetoc_loyalty";

$conn = new mysqli($servername, $username, $password, $dbname);

function generateCardNumber($conn) {
    $uniqueNumber = "";
    while (true) {
        $uniqueNumber = mt_rand(100000, 999999);

        $sql = "SELECT * FROM clients WHERE card_number = '$uniqueNumber'";
        $result = $conn->query($sql);

        if ($result->num_rows == 0) {
            break;
        }
    }
    return $uniqueNumber;
}

// Generate a unique card number
$cardNumber = generateCardNumber($conn);


function printCard($fullName, $cardNumber) {
    $imageUrl = "https://api.qrserver.com/v1/create-qr-code/?size=130x130&data=https://card.theitmogul.co.za?cardNumber={$cardNumber}&color=000000&bgcolor=eeecde";

    $imageData = file_get_contents($imageUrl);
    $filePath = __DIR__.'/assets/img/qr-code.png';
    $result = file_put_contents($filePath, $imageData);
    $cleanName = str_replace(' ', '', $fullName);
    $cardName = "assets/cards/{$cleanName}_{$cardNumber}.pdf";

    $pdf = new FPDF('L', 'in', array(6.00,4.00));

    $pdf->SetTopMargin(0);
    $pdf->Image('assets/img/background.png', null, null, 6, 4);
    $pdf->SetTextColor(238, 236, 222);
    $pdf->SetXY(0.4, 3.05);
    $pdf->SetFont('Arial','',18);
    $pdf->Cell(1.44, 0.13, $fullName, 0, 0, 'L');

    $pdf->SetXY(3.9, 1);
    $pdf->Image('assets/img/qr-code.png');

    $pdf->SetTextColor(21, 102, 105);
    $pdf->SetXY(3.82, 2.6);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(1.44, 0.13, "Card Number: {$cardNumber}", 0, 0, 'C');
    $pdf->Output('F', $cardName);

    return $cardName;

}

if(isset($_POST)) {
    $fullName = $_POST['fullName'];
    $email = $_POST["email"];
    $phoneNumber = $_POST["phoneNumber"];
    $cardName = printCard($fullName, $cardNumber);

    $stmt = $conn->prepare("INSERT INTO clients (full_name, email, phone_number, card_number) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $fullName, $email, $phoneNumber, $cardNumber);

    // Execute the statement
    $stmt->execute();

    // Close the statement and the database connection
    $stmt->close();
    $conn->close();

    echo $cardName;
    
}


?>