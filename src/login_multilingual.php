<?php
session_start();
require_once 'language_manager.php';

// Database connection with error handling
$conn = null;
try {
    $conn = new mysqli("localhost", "root", "", "crop");
    if ($conn->connect_error) {
        error_log("Database connection failed: " . $conn->connect_error);
        $conn = null;
    }
} catch (Exception $e) {
    error_log("Database connection exception: " . $e->getMessage());
    $conn = null;
}

$message = "";
$showSuccess = false;

// Signup Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($name) && !empty($email) && !empty($password)) {
        if ($conn !== null) {
            $checkSql = "SELECT * FROM farmer_profiles WHERE email=?";
            $stmt = $conn->prepare($checkSql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $checkResult = $stmt->get_result();

            if ($checkResult->num_rows > 0) {
                $message = __("email_already_registered");
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO farmer_profiles (name, email, password) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $name, $email, $hashedPassword);
                
                if ($stmt->execute()) {
                    $_SESSION['user_name'] = $name;
                    $_SESSION['user_email'] = $email;
                    $_SESSION['logged_in'] = true;
                    $_SESSION['user_type'] = 'farmer';
                    $showSuccess = true;
                    header("Location: homePage.php");
                    exit();
                } else {
                    $message = __("registration_failed");
                }
            }
        } else {
            $message = __("database_error");
        }
    } else {
        $message = __("fill_all_fields");
    }
}

// Login Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        if ($conn !== null) {
            $sql = "SELECT * FROM farmer_profiles WHERE email=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['logged_in'] = true;
                    $_SESSION['user_type'] = $user['user_type'] ?? 'farmer';
                    
                    if ($user['user_type'] === 'admin') {
                        header("Location: admin_subsidies.php");
                    } else {
                        header("Location: homePage.php");
                    }
                    exit();
                } else {
                    $message = __("login_failed");
                }
            } else {
                $message = __("login_failed");
            }
        } else {
            $message = __("database_error");
        }
    } else {
        $message = __("fill_all_fields");
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo getCurrentLanguage(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __('login'); ?> - AgriGrow</title>
    <link rel="icon" href="../photos/home/favicon2.svg" type="image/svg+xml">
    <link href="./output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="./language.js"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <!-- Header with Language Toggle -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800"><?php echo __('login'); ?></h1>
            
            <!-- Language Toggle Button -->
            <form method="POST" class="inline" data-language-toggle>
                <input type="hidden" name="toggle_language" value="1">
                <input type="hidden" name="redirect_url" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
                <button type="submit" class="px-3 py-1 rounded border border-gray-300 text-sm hover:bg-gray-50" data-current-language="<?php echo getCurrentLanguage(); ?>">
                    <?php echo getCurrentLanguageDisplayName(); ?>
                </button>
            </form>
        </div>

        <?php if (!empty($message)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="POST" class="space-y-4">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700"><?php echo __('email'); ?></label>
                <input type="email" id="email" name="email" required 
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-lime-500 focus:border-lime-500">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700"><?php echo __('password'); ?></label>
                <input type="password" id="password" name="password" required 
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-lime-500 focus:border-lime-500">
            </div>

            <button type="submit" name="login" 
                    class="w-full bg-lime-500 text-white py-2 px-4 rounded-md hover:bg-lime-600 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2">
                <?php echo __('login'); ?>
            </button>
        </form>

        <!-- Registration Form -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 mb-4"><?php echo __('register'); ?></h2>
            <form method="POST" class="space-y-4">
                <div>
                    <label for="reg_name" class="block text-sm font-medium text-gray-700"><?php echo __('name'); ?></label>
                    <input type="text" id="reg_name" name="name" required 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-lime-500 focus:border-lime-500">
                </div>

                <div>
                    <label for="reg_email" class="block text-sm font-medium text-gray-700"><?php echo __('email'); ?></label>
                    <input type="email" id="reg_email" name="email" required 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-lime-500 focus:border-lime-500">
                </div>

                <div>
                    <label for="reg_password" class="block text-sm font-medium text-gray-700"><?php echo __('password'); ?></label>
                    <input type="password" id="reg_password" name="password" required 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-lime-500 focus:border-lime-500">
                </div>

                <button type="submit" name="signup" 
                        class="w-full bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    <?php echo __('register'); ?>
                </button>
            </form>
        </div>

        <!-- Back to Home -->
        <div class="mt-6 text-center">
            <a href="homePage.php" class="text-lime-600 hover:text-lime-800 text-sm">
                ← <?php echo __('home'); ?>
            </a>
        </div>
    </div>
</body>
</html> 