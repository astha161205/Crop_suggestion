<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection with error handling
$conn = null;
try {
    $conn = new mysqli("localhost", "root", "", "crop");
    if ($conn->connect_error) {
        // Log error but don't die - allow the application to continue
        error_log("Database connection failed: " . $conn->connect_error);
        $conn = null;
    }
} catch (Exception $e) {
    error_log("Database connection exception: " . $e->getMessage());
    $conn = null;
}

// Handle theme toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_theme'])) {
    $current_theme = $_SESSION['theme'] ?? 'dark';
    $new_theme = $current_theme === 'dark' ? 'light' : 'dark';
    
    $_SESSION['theme'] = $new_theme;
    
    // Save to database if user is logged in and database is available
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && $conn !== null) {
        $user_email = $_SESSION['user_email'];
        $sql = "UPDATE farmer_profiles SET theme = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $new_theme, $user_email);
        $stmt->execute();
    }
    
    // Return JSON response for AJAX requests
    if (isset($_POST['ajax'])) {
        header('Content-Type: application/json');
        echo json_encode(['theme' => $new_theme]);
        exit();
    }
    
    // Redirect back to the page that made the request
    $redirect_url = $_POST['redirect_url'] ?? 'profile.php';
    header("Location: $redirect_url");
    exit();
}

// Load theme from session or database
function getCurrentTheme() {
    global $conn;
    
    // Check session first
    if (isset($_SESSION['theme'])) {
        return $_SESSION['theme'];
    }
    
    // If user is logged in and database is available, try to load from database
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && $conn !== null) {
        $user_email = $_SESSION['user_email'];
        $sql = "SELECT theme FROM farmer_profiles WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $theme = $row['theme'] ?? 'dark';
            $_SESSION['theme'] = $theme;
            return $theme;
        }
    }
    
    // Default to dark theme
    $_SESSION['theme'] = 'dark';
    return 'dark';
}

// Get theme classes for CSS
function getThemeClasses() {
    $theme = getCurrentTheme();
    
    if ($theme === 'light') {
        return [
            'bg' => 'bg-gray-50',
            'text' => 'text-gray-900',
            'text_secondary' => 'text-gray-600',
            'bg_card' => 'bg-white',
            'bg_header' => 'bg-white',
            'border' => 'border-gray-200',
            'border_header' => 'border-gray-200',
            'hover' => 'hover:bg-gray-100',
            'input_bg' => 'bg-gray-100',
            'input_border' => 'border-gray-300',
            'button_primary' => 'bg-lime-500 hover:bg-lime-600',
            'button_secondary' => 'bg-gray-200 hover:bg-gray-300 text-gray-800'
        ];
    } else {
        return [
            'bg' => 'bg-gray-950',
            'text' => 'text-white',
            'text_secondary' => 'text-gray-400',
            'bg_card' => 'bg-gray-800',
            'bg_header' => 'bg-gray-950',
            'border' => 'border-gray-700',
            'border_header' => 'border-gray-900',
            'hover' => 'hover:bg-gray-700',
            'input_bg' => 'bg-gray-700',
            'input_border' => 'border-gray-600',
            'button_primary' => 'bg-lime-500 hover:bg-lime-600',
            'button_secondary' => 'bg-gray-600 hover:bg-gray-700'
        ];
    }
}

// Add theme column to database if it doesn't exist
function ensureThemeColumnExists() {
    global $conn;
    
    $sql = "SHOW COLUMNS FROM farmer_profiles LIKE 'theme'";
    $result = $conn->query($sql);
    
    if ($result->num_rows === 0) {
        $sql = "ALTER TABLE farmer_profiles ADD COLUMN theme ENUM('light', 'dark') DEFAULT 'dark'";
        $conn->query($sql);
    }
}

// Initialize theme column
ensureThemeColumnExists();
?> 