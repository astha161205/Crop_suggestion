<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST")
 {
    $userEmail = htmlspecialchars($_POST['email']);

    $userName = htmlspecialchars($_POST['name']);
    $userMessage = htmlspecialchars($_POST['message']);

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('singhalastha26@gmail.com', 'AgriGrow Support');
        $mail->addAddress($userEmail, $userName); // Send to user who submitted form

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Response To Your Query';
        $mail->Body = "
            <p>Dear <strong>$userName</strong>,</p>
            <p>Thank you for reaching out to AgriGrow. We've received your valuable feedback.</p>
            <p><em>Your Message:</em> $userMessage</p>
            <p>Best regards,<br>AgriGrow Support Team</p>
        ";

        $mail->send();
        echo "<script>alert('Mail Sent Successfully'); window.location.href = 'homePage.php';</script>";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
