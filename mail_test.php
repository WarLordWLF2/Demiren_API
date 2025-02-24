<?php
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer manually (not using Composer)
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

// Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;  // Enable verbose debug output
    $mail->isSMTP();                        // Send using SMTP
    $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server
    $mail->SMTPAuth   = true;               // Enable SMTP authentication
    $mail->Username   = 'Username Here'; // SMTP username
    $mail->Password   = 'Password Here';           // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Enable SSL encryption
    $mail->Port       = 465;                // TCP port to connect to

    // Recipients
    $mail->setFrom('SenderEmail', 'Hotel');
    $mail->addAddress('RecieverEmail', 'Customer'); // Add a recipient

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Testing if Comms is Online';
    $mail->Body    = 'Attach forgor pass word here <b>* *</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
