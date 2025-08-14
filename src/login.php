<!-- CREATE TABLE signin (
    name VARCHAR(100),
    email VARCHAR(100) PRIMARY KEY,
    password VARCHAR(255)
); -->

<?php
session_start(); // Must be the FIRST line

$dotenvPath = __DIR__ . '/../.env';
if (file_exists($dotenvPath)) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
}

require_once 'language_manager.php';

// Database connection
$host = getenv('MYSQL_HOST') ?: 'localhost';
$port = getenv('MYSQL_PORT') ?: '3306';
$dbname = getenv('MYSQL_DATABASE') ?: 'crop';
$username = getenv('MYSQL_USER') ?: 'root';
$password = getenv('MYSQL_PASSWORD') ?: '';

$conn = new mysqli($host, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
$showSuccess = false;

// Signup Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($name) && !empty($email) && !empty($password)) {
        $checkSql = "SELECT * FROM signin WHERE email='$email'";
        $checkResult = $conn->query($checkSql);

        if ($checkResult->num_rows > 0) {
            $message = "Email already registered!";
        } else {
            $sql = "INSERT INTO signin (name, email, password) VALUES ('$name', '$email', '$password')";
            if ($conn->query($sql) === TRUE) {
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                $_SESSION['logged_in'] = true;
                $showSuccess = true;
                header("Location: homePage.php");
                exit();
            } else {
                $message = "Error: " . $conn->error;
            }
        }
    } else {
        $message = "Please fill all fields!";
    }
}

// Login Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $user_type = $_POST['user_type'] ?? 'user'; // Get user type

    if (!empty($email) && !empty($password)) {
        if ($user_type === 'admin') {
            // Check admin credentials
            $sql = "SELECT * FROM admin_users WHERE email='$email' AND password='$password'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $admin = $result->fetch_assoc();
                $_SESSION['user_name'] = $admin['name'];
                $_SESSION['user_email'] = $admin['email'];
                $_SESSION['logged_in'] = true;
                $_SESSION['user_type'] = 'admin';
                header("Location: admin_subsidies.php");
                exit();
            } else {
                $message = "Invalid admin credentials.";
            }
        } else {
            // Check regular user credentials
            $sql = "SELECT * FROM signin WHERE email='$email' AND password='$password'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['logged_in'] = true;
                $_SESSION['user_type'] = 'user';
                header("Location: homePage.php");
                exit();
            } else {
                $message = "Invalid email or password.";
            }
        }
    } else {
        $message = "Please fill all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <title><?php echo __('login'); ?> - AgriGrow</title>
    <style type="text/tailwindcss">
        @layer components {
            .container.active .sign-in {
                transform: translateX(100%);
                z-index: 5;
            }
            .container.active .sign-up {
                transform: translateX(100%);
                opacity: 1;
                z-index: 10;
            }
            .container.active .toggle-container {
                transform: translateX(-100%);
                border-radius: 0 150px 100px 0;
            }
            .container.active .toggle {
                transform: translateX(50%);
            }
            .container.active .toggle-left {
                transform: translateX(0);
            }
            .container.active .toggle-right {
                transform: translateX(200%);
            }
            .form-container {
                transition: all 0.6s ease-in-out;
            }
            .toggle-container {
                transition: all 0.6s ease-in-out;
            }
            .toggle {
                transition: all 0.6s ease-in-out;
            }
            .toggle-panel {
                transition: all 0.6s ease-in-out;
            }
        }
    </style>
</head>
<body class="font-['Montserrat'] bg-[url(../photos/home/farm.jpg)] bg-cover bg-no-repeat bg-center backdrop-blur-sm">
    <div class="flex items-center justify-center flex-col h-screen w-full">
        <div class="container bg-white rounded-[30px] shadow-lg relative overflow-hidden w-full max-w-4xl min-h-[480px]" id="container">
            <div class="form-container sign-up absolute top-0 h-full w-1/2 left-0 opacity-0 z-0">
                <form class="bg-white flex items-center justify-center flex-col px-10 h-full" method="POST" action="">
                    <h1 class="text-2xl font-bold mb-4"><?php echo __('create_profile'); ?></h1>
                    <span class="text-xs mb-5"><?php echo __('or_use_email_for_registration'); ?></span>
                    <input type="text" name="name" placeholder="<?php echo __('name'); ?>" class="bg-gray-100 border-none my-2 py-2.5 px-4 text-sm rounded-lg w-full outline-none">
                    <input type="email" name="email" placeholder="<?php echo __('email'); ?>" class="bg-gray-100 border-none my-2 py-2.5 px-4 text-sm rounded-lg w-full outline-none">
                    <input type="password" name="password" placeholder="<?php echo __('password'); ?>" class="bg-gray-100 border-none my-2 py-2.5 px-4 text-sm rounded-lg w-full outline-none">
                    <button type="submit" name="signup" class="bg-indigo-800 text-white text-xs py-2.5 px-11 border border-transparent rounded-lg font-semibold tracking-wider uppercase mt-2.5 cursor-pointer">
                        <?php echo __('register'); ?>
                    </button>
                    <?php if (!empty($message)) { ?>
                        <p class="<?php echo $showSuccess ? 'text-green-600' : 'text-red-600'; ?> mt-3 text-sm font-medium" id="signupMessage"><?php echo $message; ?></p>
                    <?php } ?>
                </form>
            </div>

            <div class="form-container sign-in absolute top-0 h-full w-1/2 left-0 z-20">
                <form class="bg-white flex items-center justify-center flex-col px-10 h-full" method="POST" action="">
                    <h1 class="text-2xl font-bold mb-4"><?php echo __('login'); ?></h1>
                    
                    <span class="text-xs mb-5"><?php echo __('or_use_email_and_password'); ?></span>
                    
                    <!-- User Type Selection -->
                    <div class="flex gap-4 mb-4 w-full">
                        <label class="flex items-center">
                            <input type="radio" name="user_type" value="user" checked class="mr-2">
                            <span class="text-sm"><?php echo __('user'); ?></span>
                        </label>
                        
                    </div>
                    
                    <input type="email" name="email" placeholder="<?php echo __('email'); ?>" class="bg-gray-100 border-none my-2 py-2.5 px-4 text-sm rounded-lg w-full outline-none">
                    <input type="password" name="password" placeholder="<?php echo __('password'); ?>" class="bg-gray-100 border-none my-2 py-2.5 px-4 text-sm rounded-lg w-full outline-none">
                    
                    <button class="bg-indigo-800 text-white text-xs mt-3 py-2.5 px-11 border border-transparent rounded-lg font-semibold tracking-wider uppercase cursor-pointer" name="login">
                        <?php echo __('login'); ?>
                    </button>
                    <?php if (!empty($message) && isset($_POST['signin'])) { ?>
                        <p class="text-red-600 mt-3 text-sm font-medium"><?php echo $message; ?></p>
                    <?php } ?>
                </form>
            </div>

            <div class="toggle-container absolute top-0 left-1/2 w-1/2 h-full overflow-hidden rounded-[150px_0_0_100px] z-30">
                <div class="toggle bg-gradient-to-r from-indigo-600 to-indigo-800 h-full text-white relative left-[-100%] w-[200%]">
                    <div class="toggle-panel toggle-left absolute w-1/2 h-full flex items-center justify-center flex-col px-8 text-center top-0 transform -translate-x-[200%]">
                        <h1 class="text-2xl font-bold mb-2"><?php echo __('welcome_back'); ?>!</h1>
                        <p class="text-sm leading-5 tracking-wider my-5"><?php echo __('enter_personal_details'); ?></p>
                        <button class="bg-transparent border border-white text-white text-xs py-2.5 px-11 rounded-lg font-semibold tracking-wider uppercase cursor-pointer" id="login">
                            <?php echo __('login'); ?>
                        </button>
                    </div>
                    <div class="toggle-panel toggle-right absolute w-1/2 h-full flex items-center justify-center flex-col px-8 text-center top-0 right-0 transform translate-x-0">
                        <h1 class="text-2xl font-bold mb-2"><?php echo __('Welcome to AgriGrow'); ?>!</h1>
                        <p class="text-sm leading-5 tracking-wider my-5"><?php echo __('Create your free account to access all features.'); ?></p>
                        <button class="bg-transparent border border-white text-white text-xs py-2.5 px-11 rounded-lg font-semibold tracking-wider uppercase cursor-pointer" id="register">
                            <?php echo __('register'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const container = document.getElementById('container');
        const registerBtn = document.getElementById('register');
        const loginBtn = document.getElementById('login');
        const signupMessage = document.getElementById('signupMessage');

        registerBtn.addEventListener('click', () => {
            container.classList.add("active");
        });

        loginBtn.addEventListener('click', () => {
            container.classList.remove("active");
        });

        <?php if (!empty($message) && isset($_POST['signup'])) { ?>
            <?php if ($showSuccess) { ?>
                setTimeout(() => {
                    container.classList.remove("active");
                }, 2000);
            <?php } else { ?>
                container.classList.add("active");
            <?php } ?>
        <?php } ?>
    </script>
</body>
</html>