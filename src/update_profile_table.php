<?php
require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();
// Script to update farmer_profiles table for profile image paths
// Run this script in your browser to update the table structure

echo "<h2>Updating farmer_profiles Table</h2>";

// Database connection
$conn = new mysqli(
    $_ENV['MYSQL_HOST'],
    $_ENV['MYSQL_USER'],
    $_ENV['MYSQL_PASSWORD'],
    $_ENV['MYSQL_DATABASE'],
    $_ENV['MYSQL_PORT']
);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<p>✓ Connected to database successfully</p>";

// Check if profile_image_path column already exists
$result = $conn->query("SHOW COLUMNS FROM farmer_profiles LIKE 'profile_image_path'");
if ($result->num_rows > 0) {
    echo "<p>✓ profile_image_path column already exists</p>";
} else {
    // Add new column for profile image path
    $sql = "ALTER TABLE farmer_profiles ADD COLUMN profile_image_path VARCHAR(255) AFTER profile_image";
    if ($conn->query($sql) === TRUE) {
        echo "<p>✓ Added profile_image_path column successfully</p>";
    } else {
        echo "<p>✗ Error adding column: " . $conn->error . "</p>";
    }
}

// Verify table structure
$result = $conn->query("DESCRIBE farmer_profiles");
if ($result) {
    echo "<h3>Updated Table Structure:</h3>";
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "<h3>✅ farmer_profiles table has been successfully updated!</h3>";
echo "<p>Now profile images will be stored as file paths instead of binary data, preventing the max_allowed_packet error.</p>";
echo "<p><a href='profile.php'>← Back to Profile</a></p>";

$conn->close();
?>

