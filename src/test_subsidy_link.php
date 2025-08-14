<?php
// Test page to verify subsidy linking functionality
session_start();

// Load environment variables from .env
require __DIR__ . '/../vendor/autoload.php'; // adjust path if needed
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo "<p style='color: red;'>Please login first!</p>";
    exit();
}

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

echo "<h2>Testing Subsidy Linking</h2>";

// Get subsidy details if subsidy_id is provided in URL
$selected_subsidy = null;
$subsidy_id = null;

if (isset($_GET['subsidy_id']) && !empty($_GET['subsidy_id'])) {
    $subsidy_id = $_GET['subsidy_id'];
    echo "<p>✅ Received subsidy_id: " . $subsidy_id . "</p>";
    
    // Get subsidy details
    $subsidy_query = "SELECT * FROM subsidies WHERE id = ? AND status = 'active'";
    $subsidy_stmt = $conn->prepare($subsidy_query);
    $subsidy_stmt->bind_param("i", $subsidy_id);
    $subsidy_stmt->execute();
    $subsidy_result = $subsidy_stmt->get_result();
    
    if ($subsidy_result->num_rows > 0) {
        $selected_subsidy = $subsidy_result->fetch_assoc();
        echo "<p>✅ Found subsidy: " . htmlspecialchars($selected_subsidy['title']) . "</p>";
    } else {
        echo "<p style='color: red;'>❌ Subsidy not found or not active</p>";
    }
} else {
    echo "<p>ℹ️ No subsidy_id provided in URL</p>";
}

// Show available subsidies for testing
echo "<h3>Available Subsidies for Testing:</h3>";
$subsidies_query = "SELECT id, title FROM subsidies WHERE status = 'active' LIMIT 5";
$subsidies_result = $conn->query($subsidies_query);

if ($subsidies_result->num_rows > 0) {
    echo "<ul>";
    while ($subsidy = $subsidies_result->fetch_assoc()) {
        echo "<li><a href='test_subsidy_link.php?subsidy_id=" . $subsidy['id'] . "'>" . htmlspecialchars($subsidy['title']) . "</a></li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color: red;'>No active subsidies found in database</p>";
}

echo "<p><a href='my_applications.php'>← Back to My Applications</a></p>";
echo "<p><a href='SUNSIDIES.php'>← Back to Subsidies</a></p>";
?>

