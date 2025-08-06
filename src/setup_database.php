<?php
// Database Setup Script
// Run this script once to set up your database

echo "<h2>Database Setup for Crop Suggestion Application</h2>";

// Connect to MySQL server (without specifying database)
$conn = new mysqli("localhost", "root", "");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<p>✓ Connected to MySQL server successfully</p>";

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS crop";
if ($conn->query($sql) === TRUE) {
    echo "<p>✓ Database 'crop' created or already exists</p>";
} else {
    echo "<p>✗ Error creating database: " . $conn->error . "</p>";
}

// Select the database
$conn->select_db("crop");

// Create farmer_profiles table
$sql = "CREATE TABLE IF NOT EXISTS farmer_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    address TEXT,
    farm_size DECIMAL(10,2),
    theme ENUM('light', 'dark') DEFAULT 'dark',
    user_type ENUM('farmer', 'admin') DEFAULT 'farmer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "<p>✓ Table 'farmer_profiles' created successfully</p>";
} else {
    echo "<p>✗ Error creating table: " . $conn->error . "</p>";
}

// Create subsidies table
$sql = "CREATE TABLE IF NOT EXISTS subsidies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    amount DECIMAL(12,2),
    eligibility_criteria TEXT,
    application_deadline DATE,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "<p>✓ Table 'subsidies' created successfully</p>";
} else {
    echo "<p>✗ Error creating table: " . $conn->error . "</p>";
}

// Create subsidy_applications table
$sql = "CREATE TABLE IF NOT EXISTS subsidy_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    farmer_id INT,
    subsidy_id INT,
    application_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    documents_submitted TEXT,
    FOREIGN KEY (farmer_id) REFERENCES farmer_profiles(id) ON DELETE CASCADE,
    FOREIGN KEY (subsidy_id) REFERENCES subsidies(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "<p>✓ Table 'subsidy_applications' created successfully</p>";
} else {
    echo "<p>✗ Error creating table: " . $conn->error . "</p>";
}

// Create crop_recommendations table
$sql = "CREATE TABLE IF NOT EXISTS crop_recommendations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    farmer_id INT,
    soil_type VARCHAR(50),
    climate_zone VARCHAR(50),
    season VARCHAR(20),
    recommended_crops TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (farmer_id) REFERENCES farmer_profiles(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "<p>✓ Table 'crop_recommendations' created successfully</p>";
} else {
    echo "<p>✗ Error creating table: " . $conn->error . "</p>";
}

// Insert sample subsidies data
$subsidies = [
    ['PM-KISAN Scheme', 'Direct income support of Rs. 6000 per year to eligible farmer families', 6000.00, 'Small and marginal farmers with landholding up to 2 hectares', '2024-12-31'],
    ['PM Fasal Bima Yojana', 'Crop insurance scheme to protect farmers against crop loss', 5000.00, 'All farmers growing notified crops', '2024-11-30'],
    ['Soil Health Card Scheme', 'Free soil testing and recommendations for farmers', 0.00, 'All farmers', '2024-10-31'],
    ['PMKSY - Micro Irrigation', 'Subsidy for drip and sprinkler irrigation systems', 15000.00, 'Farmers with landholding up to 5 hectares', '2024-09-30'],
    ['National Agriculture Market (eNAM)', 'Online trading platform for agricultural commodities', 2000.00, 'All registered farmers', '2024-08-31']
];

$stmt = $conn->prepare("INSERT IGNORE INTO subsidies (title, description, amount, eligibility_criteria, application_deadline) VALUES (?, ?, ?, ?, ?)");

foreach ($subsidies as $subsidy) {
    $stmt->bind_param("ssdss", $subsidy[0], $subsidy[1], $subsidy[2], $subsidy[3], $subsidy[4]);
    $stmt->execute();
}

echo "<p>✓ Sample subsidies data inserted</p>";

// Create admin user (password: admin123)


echo "<p>✓ Admin user created (email: admin@agrigrow.com, password: admin123)</p>";

$conn->close();

echo "<h3>Database setup completed successfully!</h3>";
echo "<p>You can now run your application. Make sure XAMPP's MySQL service is running.</p>";
echo "<p><a href='homePage.php'>Go to Homepage</a></p>";
?> 