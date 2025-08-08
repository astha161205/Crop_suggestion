<?php
// Script to recreate the user_applications table
// Run this script in your browser to recreate the dropped table

echo "<h2>Recreating user_applications Table</h2>";

// Database connection
$conn = new mysqli("localhost", "root", "", "crop");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<p>✓ Connected to database successfully</p>";

// Drop table if it exists
$sql = "DROP TABLE IF EXISTS user_applications";
if ($conn->query($sql) === TRUE) {
    echo "<p>✓ Dropped existing user_applications table (if existed)</p>";
} else {
    echo "<p>✗ Error dropping table: " . $conn->error . "</p>";
}

// Create user_applications table
$sql = "CREATE TABLE user_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_email VARCHAR(100) NOT NULL,
    subsidy_id INT NOT NULL,
    application_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'approved', 'rejected', 'under_review') DEFAULT 'pending',
    notes TEXT,
    
    -- Personal Details
    full_name VARCHAR(100),
    phone_number VARCHAR(15),
    address TEXT,
    district VARCHAR(100),
    state VARCHAR(100),
    pincode VARCHAR(10),
    
    -- Document Uploads (Image file paths)
    aadhar_card_image VARCHAR(255),
    pan_card_image VARCHAR(255),
    bank_passbook_image VARCHAR(255),
    land_documents_image VARCHAR(255),
    income_certificate_image VARCHAR(255),
    caste_certificate_image VARCHAR(255),
    profile_photo_image VARCHAR(255),
    signature_image VARCHAR(255),
    other_documents_images TEXT,
    
    -- Additional Details
    land_holding DECIMAL(10,2),
    annual_income DECIMAL(12,2),
    bank_account_number VARCHAR(50),
    ifsc_code VARCHAR(20),
    bank_name VARCHAR(100),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (subsidy_id) REFERENCES subsidies(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "<p>✓ user_applications table created successfully</p>";
} else {
    echo "<p>✗ Error creating table: " . $conn->error . "</p>";
}

// Add indexes for better performance
$indexes = [
    "CREATE INDEX idx_user_email ON user_applications(user_email)",
    "CREATE INDEX idx_subsidy_id ON user_applications(subsidy_id)",
    "CREATE INDEX idx_status ON user_applications(status)",
    "CREATE INDEX idx_application_date ON user_applications(application_date)"
];

foreach ($indexes as $index_sql) {
    if ($conn->query($index_sql) === TRUE) {
        echo "<p>✓ Index created successfully</p>";
    } else {
        echo "<p>⚠ Index creation warning: " . $conn->error . "</p>";
    }
}

// Verify table structure
$result = $conn->query("DESCRIBE user_applications");
if ($result) {
    echo "<h3>Table Structure:</h3>";
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

// Check if subsidies table exists (required for foreign key)
$result = $conn->query("SHOW TABLES LIKE 'subsidies'");
if ($result->num_rows > 0) {
    echo "<p>✓ subsidies table exists (foreign key constraint will work)</p>";
} else {
    echo "<p>⚠ Warning: subsidies table does not exist. Foreign key constraint may fail.</p>";
}

echo "<h3>✅ user_applications table has been successfully recreated!</h3>";
echo "<p><a href='homePage.php'>← Back to Home</a></p>";

$conn->close();
?>
