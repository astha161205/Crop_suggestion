<?php
// Test script to verify email configuration
// Access this file in your browser to test email setup

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

echo "<h2>Email Configuration Test</h2>";

// Check environment variables
$emailUser = $_ENV['EMAIL_USER'] ?? getenv('EMAIL_USER');
$emailPass = $_ENV['EMAIL_PASS'] ?? getenv('EMAIL_PASS');

echo "<h3>Environment Variables:</h3>";
echo "<p><strong>EMAIL_USER:</strong> " . ($emailUser ? "✅ Set" : "❌ Not set") . "</p>";
echo "<p><strong>EMAIL_PASS:</strong> " . ($emailPass ? "✅ Set" : "❌ Not set") . "</p>";

if (!$emailUser || !$emailPass) {
    echo "<p style='color: red;'>❌ Email configuration incomplete. Please set EMAIL_USER and EMAIL_PASS environment variables.</p>";
    exit;
}

// Test SMTP connection
echo "<h3>SMTP Connection Test:</h3>";
$mail = new PHPMailer(true);

try {
    $mail->SMTPDebug = 2; // Enable verbose debug output
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $emailUser;
    $mail->Password = $emailPass;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    
    // Test connection
    $mail->smtpConnect();
    echo "<p style='color: green;'>✅ SMTP connection successful!</p>";
    
    // Test sending a simple email
    $mail->setFrom($emailUser, 'AgriGrow Test');
    $mail->addAddress($emailUser, 'Test User');
    $mail->Subject = 'Test Email from AgriGrow';
    $mail->Body = 'This is a test email to verify your email configuration is working.';
    
    $mail->send();
    echo "<p style='color: green;'>✅ Test email sent successfully!</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<h3>Next Steps:</h3>";
echo "<p>If you see ✅ marks above, your email configuration is working correctly.</p>";
echo "<p>If you see ❌ marks, please check:</p>";
echo "<ul>";
echo "<li>Gmail 2-Factor Authentication is enabled</li>";
echo "<li>App password is generated and correct</li>";
echo "<li>Environment variables are set in your deployment platform</li>";
echo "</ul>";
?>
