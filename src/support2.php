<?php

// Load Composer autoloader
require __DIR__ . '/../vendor/autoload.php';

// Load environment variables if .env file exists
$dotenvPath = __DIR__ . '/../.env';
if (file_exists($dotenvPath)) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userEmail = htmlspecialchars($_POST['email']);
    $userName = htmlspecialchars($_POST['name']);
    $userMessage = htmlspecialchars($_POST['message']);

    // Check if environment variables are set
    $emailUser = $_ENV['EMAIL_USER'] ?? getenv('EMAIL_USER');
    $emailPass = $_ENV['EMAIL_PASS'] ?? getenv('EMAIL_PASS');
    
    if (!$emailUser || !$emailPass) {
        echo "<script>alert('Email configuration not found. Please contact administrator.'); window.location.href = 'index.php';</script>";
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $emailUser;
        $mail->Password = $emailPass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom($emailUser, 'AgriGrow Support');
        $mail->addAddress($userEmail, $userName);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Response To Your Query';
        $mail->Body = "
            <p>Dear <strong>$userName</strong>,</p>
            <p>Thank you for reaching out to AgriGrow. We've received your message and will get back to you shortly.</p>
            <p><em>Your Message:</em> $userMessage</p>
            <p>Best regards,<br>AgriGrow Support Team</p>
        ";

        $mail->send();
        echo "<script>alert('Mail Sent Successfully'); window.location.href = 'index.php';</script>";
    } catch (Exception $e) {
        error_log("PHPMailer Error: " . $e->getMessage());
        echo "<script>alert('Failed to send email. Please try again later.'); window.location.href = 'index.php';</script>";
    }
}
?>
