<?php
session_start();
require_once 'theme_manager.php';
require_once 'language_manager.php';
$theme = getThemeClasses();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "crop");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get email from login session
$email = $_SESSION['user_email'] ?? '';
$profileData = null;
$isEditing = isset($_GET['edit']) && $_GET['edit'] === 'true';
$message = '';

// Fetch user profile data
$sql = "SELECT * FROM farmer_profiles WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $profileData = $result->fetch_assoc();
    $_SESSION['has_profile'] = true;
} else {
    $_SESSION['has_profile'] = false;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $farm_name = $_POST['farm_name'] ?? '';
    $farm_size = $_POST['farm_size'] ?? '';
    $location = $_POST['location'] ?? '';

    // Handle profile image upload with new file path method
    $profile_image_path = null;
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        // Create uploads directory if it doesn't exist
        $upload_dir = "uploads/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $profile_image_path = uploadProfileImage($_FILES['profile_image'], $upload_dir, $email);
        
        // Check if upload was successful
        if (strpos($profile_image_path, 'ERROR:') === 0) {
            $message = $profile_image_path;
        }
    }

    if (empty($message)) { // Only proceed if no upload errors
        if ($profileData) {
            // Update existing profile
            if ($profile_image_path !== null) {
                $sql = "UPDATE farmer_profiles SET name=?, farm_name=?, farm_size=?, location=?, profile_image_path=? WHERE email=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssdsss", $name, $farm_name, $farm_size, $location, $profile_image_path, $email);
            } else {
                $sql = "UPDATE farmer_profiles SET name=?, farm_name=?, farm_size=?, location=? WHERE email=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssdss", $name, $farm_name, $farm_size, $location, $email);
            }
        } else {
            // Create new profile
            if ($profile_image_path !== null) {
                $sql = "INSERT INTO farmer_profiles (name, email, farm_name, farm_size, location, profile_image_path) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssdss", $name, $email, $farm_name, $farm_size, $location, $profile_image_path);
            } else {
                $sql = "INSERT INTO farmer_profiles (name, email, farm_name, farm_size, location) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssdss", $name, $email, $farm_name, $farm_size, $location);
            }
        }

        if ($stmt->execute()) {
            $_SESSION['has_profile'] = true;
            header("Location: profile.php");
            exit();
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}

// Function to handle profile image upload with validation and compression
function uploadProfileImage($file, $upload_dir, $email) {
        // Check file size (max 5MB)
        $max_size = 5 * 1024 * 1024; // 5MB in bytes
        if ($file['size'] > $max_size) {
            return "ERROR: File too large. Maximum size is 5MB.";
        }
        
        // Check file type
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowed_types)) {
            return "ERROR: Invalid file type. Only JPG, PNG, and GIF are allowed.";
        }
        
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $file_name = 'profile_' . $email . '_' . time() . '_' . uniqid() . '.' . $file_extension;
        $file_path = $upload_dir . $file_name;
        
        // Compress image if it's too large and GD is available
        if ($file['size'] > 1024 * 1024 && extension_loaded('gd')) { // If larger than 1MB and GD available
            $file_path = compressProfileImage($file['tmp_name'], $file_path, $file['type']);
        } else {
            if (!move_uploaded_file($file['tmp_name'], $file_path)) {
                return "ERROR: Failed to upload file.";
            }
        }
        
        return $file_path;
    }
    
    // Function to compress profile image
    function compressProfileImage($source_path, $destination_path, $image_type) {
        // Check if GD library is available
        if (!extension_loaded('gd')) {
            // Fallback: just copy the file without compression
            if (copy($source_path, $destination_path)) {
                return $destination_path;
            } else {
                return "ERROR: Failed to copy file (GD not available).";
            }
        }
        
        $max_width = 800;
        $max_height = 800;
        $quality = 85;
        
        list($width, $height) = getimagesize($source_path);
        
        // Calculate new dimensions
        if ($width > $max_width || $height > $max_height) {
            $ratio = min($max_width / $width, $max_height / $height);
            $new_width = round($width * $ratio);
            $new_height = round($height * $ratio);
        } else {
            $new_width = $width;
            $new_height = $height;
        }
        
        // Create new image
        $new_image = imagecreatetruecolor($new_width, $new_height);
        
        // Load source image
        switch ($image_type) {
            case 'image/jpeg':
            case 'image/jpg':
                $source_image = imagecreatefromjpeg($source_path);
                break;
            case 'image/png':
                $source_image = imagecreatefrompng($source_path);
                // Preserve transparency for PNG
                imagealphablending($new_image, false);
                imagesavealpha($new_image, true);
                break;
            case 'image/gif':
                $source_image = imagecreatefromgif($source_path);
                break;
            default:
                return "ERROR: Unsupported image type.";
        }
        
        // Resize image
        imagecopyresampled($new_image, $source_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        
        // Save compressed image
        switch ($image_type) {
            case 'image/jpeg':
            case 'image/jpg':
                imagejpeg($new_image, $destination_path, $quality);
                break;
            case 'image/png':
                imagepng($new_image, $destination_path, round($quality / 10));
                break;
            case 'image/gif':
                imagegif($new_image, $destination_path);
                break;
        }
        
        // Clean up
        imagedestroy($source_image);
        imagedestroy($new_image);
        
        return $destination_path;
    }

// Function to get profile image
function getProfileImage($profileData) {
    // Try new column first
    if (isset($profileData['profile_image_path']) && $profileData['profile_image_path']) {
        return $profileData['profile_image_path'];
    }
    // Fallback to old binary data method
    if (isset($profileData['profile_image']) && $profileData['profile_image']) {
        return 'data:image/jpeg;base64,' . base64_encode($profileData['profile_image']);
    }
    return '../photos/home/default-profile.png';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - AgriGrow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="./language.js"></script>
    <style>
        .profile-section {
            transition: all 0.3s ease;
        }
        .profile-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        .input-field {
            transition: all 0.3s ease;
        }
        .input-field:focus {
            box-shadow: 0 0 0 2px #4ade80;
        }
        .btn-primary {
            background: linear-gradient(135deg, #4ade80, #3b82f6);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(74, 222, 128, 0.4);
        }
    </style>
</head>
<body class="font-mono <?php echo $theme['bg']; ?> <?php echo $theme['text']; ?> relative">



<header class="flex justify-between items-center <?php echo $theme['bg_header']; ?> h-15 sticky z-20 border-b-2 <?php echo $theme['border_header']; ?> top-0 pl-3 pr-3">
    <div class="flex gap-2 items-center">
        <a href="./homePage.php" class="flex items-center gap-2">
            <img src="../photos/home/logo.png" alt="logo" class="h-10 w-10 rounded-4xl">
            <h3 class="">AgriGrow</h3>
        </a>
    </div>

    <div class="<?php echo $theme['text_secondary']; ?> flex gap-6 pl-0 pr-4 pt-1 pb-1 ml-auto">
        <a href="./homePage.php" class="<?php echo $theme['hover']; ?>"><?php echo __('home'); ?></a>
        <a href="./SUNSIDIES.php" class="<?php echo $theme['hover']; ?>"><?php echo __('subsidies'); ?></a>
        <a href="./blog.php" class="<?php echo $theme['hover']; ?>"><?php echo __('blog'); ?></a>
        <a href="./homePage.php#About" class="<?php echo $theme['hover']; ?>"><?php echo __('about_us'); ?></a>
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
            <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
                <a href="./admin_subsidies.php" class="<?php echo $theme['hover']; ?>"><?php echo __('admin_panel'); ?></a>
                <a href="./logout.php" class="<?php echo $theme['hover']; ?> text-red-400"><?php echo __('logout'); ?></a>
            <?php else: ?>
                <a href="./profile.php" class="<?php echo $theme['hover']; ?>"><?php echo __('profile'); ?></a>
            <?php endif; ?>
        <?php else: ?>
            <a href="./login.php" class="<?php echo $theme['hover']; ?>"><?php echo __('login'); ?></a>
        <?php endif; ?>
    </div>
</header>
    

    <div class="container mx-auto px-4 py-8">
        <?php if ($message): ?>
            <div class="mb-6 p-4 bg-red-500/20 border border-red-500 rounded-lg text-red-400">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <?php if ($_SESSION['has_profile'] && !$isEditing): ?>
            <!-- Profile View -->
            <div class="max-w-4xl mx-auto">
                <form method="POST" enctype="multipart/form-data" id="profile-image-form" class="hidden">
                    <input type="file" name="profile_image" id="profile_image" accept="image/*" class="hidden" onchange="handleImageUpload()">
                </form>
                <!-- Profile Header -->
                <div class="<?php echo $theme['bg_card']; ?> rounded-xl shadow-xl overflow-hidden mb-8">
                    <div class="relative">
                        <!-- Cover Photo -->
                        <div class="h-40 bg-[url('../photos/home/back1.jpg')] bg-cover "></div>
                        
                        <!-- Profile Picture and Basic Info -->
                        <div class="flex flex-col md:flex-row items-start px-6 pb-6 -mt-16">
                            <div class="relative group">
                                <form method="POST" enctype="multipart/form-data" id="profile-image-form">
                                    <img src="<?php echo getProfileImage($profileData); ?>" alt="Profile" class="w-32 h-32 rounded-full border-4 <?php echo $theme['border']; ?> object-cover">
                                    <label for="profile_image" class="absolute bottom-2 right-2 bg-lime-500 hover:bg-lime-600 p-2 rounded-full transition-all opacity-0 group-hover:opacity-100 cursor-pointer">
                                        <i class="fas fa-camera text-white text-sm"></i>
                                    </label>
                                    <input type="file" name="profile_image" id="profile_image" accept="image/*" class="hidden" onchange="handleImageUpload(this)">
                                </form>
                            </div>
                            
                            <div class="md:ml-6 mt-4 md:mt-0 w-full">
                                <div class="flex flex-col md:flex-row md:items-end justify-between">
                                    <div>
                                        <h1 class="text-3xl font-bold <?php echo $theme['text']; ?>"><?php echo htmlspecialchars($profileData['name']); ?></h1>
                                        <p class="<?php echo $theme['text_secondary']; ?>"><?php echo htmlspecialchars($profileData['email']); ?></p>
                                    </div>
                                    <div class="mt-3 md:mt-0">
                                        <span class="inline-block bg-lime-500/20 text-lime-400 px-3 py-1 rounded-full text-sm font-medium"><?php echo __('farmer'); ?></span>
                                    </div>
                                </div>
                                
                                <!-- Edit Button -->
                                <div class="flex gap-3 mt-4">
                                    <a href="?edit=true" class="<?php echo $theme['button_primary']; ?> px-4 py-2 rounded-lg font-medium text-white">
                                        <i class="fas fa-edit mr-2"></i><?php echo __('edit_profile'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Personal Info -->
                    <div class="<?php echo $theme['bg_card']; ?> rounded-xl p-6 border <?php echo $theme['border']; ?> profile-section">
                        <h2 class="text-xl font-bold mb-4 pb-2 border-b <?php echo $theme['border']; ?> <?php echo $theme['text']; ?>"><?php echo __('personal_information'); ?></h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm <?php echo $theme['text_secondary']; ?> mb-1"><?php echo __('full_name'); ?></label>
                                <p class="font-medium <?php echo $theme['text']; ?>"><?php echo htmlspecialchars($profileData['name']); ?></p>
                            </div>
                            <div>
                                <label class="block text-sm <?php echo $theme['text_secondary']; ?> mb-1"><?php echo __('email'); ?></label>
                                <p class="font-medium <?php echo $theme['text']; ?>"><?php echo htmlspecialchars($profileData['email']); ?></p>
                            </div>
                            <div>
                                <label class="block text-sm <?php echo $theme['text_secondary']; ?> mb-1"><?php echo __('location'); ?></label>
                                <p class="font-medium <?php echo $theme['text']; ?>"><?php echo htmlspecialchars($profileData['location']); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Farm Info -->
                    <div class="<?php echo $theme['bg_card']; ?> rounded-xl p-6 border <?php echo $theme['border']; ?> profile-section">
                        <h2 class="text-xl font-bold mb-4 pb-2 border-b <?php echo $theme['border']; ?> <?php echo $theme['text']; ?>"><?php echo __('farm_information'); ?></h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm <?php echo $theme['text_secondary']; ?> mb-1"><?php echo __('farm_name'); ?></label>
                                <p class="font-medium <?php echo $theme['text']; ?>"><?php echo htmlspecialchars($profileData['farm_name']); ?></p>
                            </div>
                            <div>
                                <label class="block text-sm <?php echo $theme['text_secondary']; ?> mb-1"><?php echo __('farm_size'); ?></label>
                                <p class="font-medium <?php echo $theme['text']; ?>"><?php echo htmlspecialchars($profileData['farm_size']); ?> acres</p>
                            </div>
                            <div>
                                <label class="block text-sm <?php echo $theme['text_secondary']; ?> mb-1"><?php echo __('member_since'); ?></label>
                                <p class="font-medium <?php echo $theme['text']; ?>"><?php echo date('F j, Y', strtotime($profileData['created_at'])); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Profile Edit Form -->
            <div class="max-w-2xl mx-auto">
                <div class="<?php echo $theme['bg_card']; ?> rounded-xl shadow-xl p-8 border <?php echo $theme['border']; ?>">
                    <h1 class="text-2xl font-bold mb-6 <?php echo $theme['text']; ?>">
                        <?php echo $profileData ? 'Edit Profile' : 'Create Profile'; ?>
                    </h1>
                    
                    <form method="POST" enctype="multipart/form-data" class="space-y-6">
                        <!-- Profile Image Upload -->
                        <div class="text-center">
                            <div class="relative inline-block">
                                <img id="profile-preview" src="<?php echo getProfileImage($profileData); ?>" alt="Profile Preview" class="w-32 h-32 rounded-full border-4 <?php echo $theme['border']; ?> object-cover mx-auto">
                                <label for="profile_image" class="absolute bottom-2 right-2 bg-lime-500 hover:bg-lime-600 p-2 rounded-full transition-all cursor-pointer">
                                    <i class="fas fa-camera text-white text-sm"></i>
                                </label>
                            </div>
                            <input type="file" name="profile_image" id="profile_image" accept="image/*" class="hidden" onchange="previewImage(this)">
                        </div>

                        <div>
                            <label class="block <?php echo $theme['text_secondary']; ?> mb-2">Full Name</label>
                            <input type="text" name="name" value="<?php echo $profileData ? htmlspecialchars($profileData['name']) : htmlspecialchars($_SESSION['user_name']); ?>" 
                                   class="input-field w-full <?php echo $theme['input_bg']; ?> <?php echo $theme['text']; ?> rounded-lg px-4 py-3 border <?php echo $theme['input_border']; ?> focus:outline-none focus:border-lime-500" required>
                        </div>
                        
                        <div>
                            <label class="block <?php echo $theme['text_secondary']; ?> mb-2">Farm Name</label>
                            <input type="text" name="farm_name" value="<?php echo $profileData ? htmlspecialchars($profileData['farm_name']) : ''; ?>" 
                                   class="input-field w-full <?php echo $theme['input_bg']; ?> <?php echo $theme['text']; ?> rounded-lg px-4 py-3 border <?php echo $theme['input_border']; ?> focus:outline-none focus:border-lime-500" required>
                        </div>
                        
                        <div>
                            <label class="block <?php echo $theme['text_secondary']; ?> mb-2">Farm Size (acres)</label>
                            <input type="number" name="farm_size" value="<?php echo $profileData ? htmlspecialchars($profileData['farm_size']) : ''; ?>" 
                                   class="input-field w-full <?php echo $theme['input_bg']; ?> <?php echo $theme['text']; ?> rounded-lg px-4 py-3 border <?php echo $theme['input_border']; ?> focus:outline-none focus:border-lime-500" required>
                        </div>
                        
                        <div>
                            <label class="block <?php echo $theme['text_secondary']; ?> mb-2"><?php echo __('location'); ?></label>
                            <input type="text" name="location" value="<?php echo $profileData ? htmlspecialchars($profileData['location']) : ''; ?>" 
                                   class="input-field w-full <?php echo $theme['input_bg']; ?> <?php echo $theme['text']; ?> rounded-lg px-4 py-3 border <?php echo $theme['input_border']; ?> focus:outline-none focus:border-lime-500" required>
                        </div>
                        
                        <div class="flex justify-end gap-4">
                            <?php if ($profileData): ?>
                                <a href="profile.php" class="<?php echo $theme['button_secondary']; ?> px-4 py-2 rounded-lg"><?php echo __('cancel'); ?></a>
                            <?php endif; ?>
                            <button type="submit" class="<?php echo $theme['button_primary']; ?> px-6 py-3 rounded-lg font-bold text-white">
                                <?php echo $profileData ? __('update_profile') : __('create_profile'); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Logout Button Section -->
    <div class="container mx-auto px-4 py-8 mt-8">
        <div class="max-w-4xl mx-auto">
            <div class="<?php echo $theme['bg_card']; ?> rounded-xl shadow-xl p-8 border <?php echo $theme['border']; ?>">
                <div class="text-center">
                    <h2 class="text-2xl font-bold mb-4 text-red-400"><?php echo __('account_management'); ?></h2>
                    <p class="<?php echo $theme['text_secondary']; ?> mb-6"><?php echo __('manage_account_settings'); ?></p>
                    
                    <!-- Theme Toggle Section -->
                    <div class="mb-8 p-6 bg-gray-100 dark:bg-gray-700 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 <?php echo $theme['text']; ?>"><?php echo __('theme_settings'); ?></h3>
                        <div class="flex items-center justify-center gap-4">
                            <span class="<?php echo $theme['text_secondary']; ?>"><?php echo __('current_theme'); ?>:</span>
                            <span class="font-semibold <?php echo $theme['text']; ?>">
                                <?php echo ucfirst(getCurrentTheme()); ?> Mode
                            </span>
                            <form method="POST" action="theme_manager.php" class="inline">
                                <input type="hidden" name="toggle_theme" value="1">
                                <input type="hidden" name="redirect_url" value="profile.php">
                                <button type="submit" class="<?php echo $theme['button_primary']; ?> text-white px-6 py-2 rounded-lg font-semibold transition-colors flex items-center gap-2">
                                    <i class="fas fa-<?php echo getCurrentTheme() === 'dark' ? 'sun' : 'moon'; ?>"></i>
                                    <?php echo __('switch_to'); ?> <?php echo getCurrentTheme() === 'dark' ? __('light_mode') : __('dark_mode'); ?>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Language Settings Section -->
                    <!-- <div class="mb-8 p-6 bg-gray-100 dark:bg-gray-700 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 </h3>
                        <div class="flex items-center justify-center gap-4">
                            <span class=":</span>
                            <span class="font-semibold ">
                                
                            </span>
                            <form method="POST" class="inline" data-language-toggle>
                                <input type="hidden" name="toggle_language" value="1">
                                <input type="hidden" name="redirect_url" value="profile.php">
                                <button type="submit" class=" text-white px-6 py-2 rounded-lg font-semibold transition-colors flex items-center gap-2">
                                    <i class="fas fa-language"></i>
                                    
                                </button>
                            </form>
                        </div>
                        <div class="mt-4 text-center">
                            <p class="text-sm <gua?php echo $theme['text_secondary']; ?>">
                               
                            </p>
                        </div>
                    </div> -->
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="./my_applications.php" 
                           class="bg-blue-500 hover:bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-file-alt"></i>
                            <?php echo __('my_applications'); ?>
                        </a>
                        
                        <a href="./logout.php" 
                           class="bg-red-500 hover:bg-red-600 text-white px-8 py-3 rounded-lg font-semibold transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-sign-out-alt"></i>
                            <?php echo __('logout'); ?>
                        </a>
                        
                        <a href="./homePage.php" 
                           class="<?php echo $theme['button_secondary']; ?> px-8 py-3 rounded-lg font-semibold transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-home"></i>
                            <?php echo __('back_to_home'); ?>
                        </a>
                    </div>
                    
                    <div class="mt-6 p-4 bg-yellow-500/10 border border-yellow-500/20 rounded-lg">
                        <p class="text-yellow-400 text-sm">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <?php echo __('logout_warning'); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="./theme.js"></script>
    <script>
        // Profile dropdown toggle
        const menuBtn = document.getElementById('menu-btn');
        const profileMenu = document.getElementById('profile-menu');
        
        menuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            profileMenu.classList.toggle('hidden');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', () => {
            profileMenu.classList.add('hidden');
        });

        // Image preview functionality
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    // Update the preview image
                    const previewImg = document.getElementById('profile-preview');
                    if (previewImg) {
                        previewImg.src = e.target.result;
                    }
                };
                reader.readAsDataURL(input.files[0]);

                // Automatically submit the form to update the image in the database
                const form = document.getElementById('profile-image-form');
                if (form) {
                    form.submit();
                }
            }
        }
    </script>
</body>
</html>