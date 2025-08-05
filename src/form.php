<?php
// MySQL DB config
$host = "localhost";        // usually localhost
$dbname = "backend";  // your database name
$username = "root";         // your phpMyAdmin username (default is root)
$password = "";             // your phpMyAdmin password (empty by default on XAMPP)

// Connect to MySQL
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize input
    $name = $conn->real_escape_string(trim($_POST["name"]));
    $email = $conn->real_escape_string(trim($_POST["email"]));
    $message = $conn->real_escape_string(trim($_POST["message"]));

    // Insert into DB
    $sql = "INSERT INTO support_requests (name, email, message) VALUES ('$name', '$email', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: limegreen;'>✅ Support request submitted successfully!</p>";
    } else {
        echo "<p style='color: red;'>❌ Error: " . $conn->error . "</p>";
    }

    $conn->close();
}
?>