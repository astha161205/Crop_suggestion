<?php
session_start();
// Load environment variables from .env
require __DIR__ . '/../vendor/autoload.php'; // adjust path if needed
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();


// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

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

$user_email = $_SESSION['user_email'];

// Get subsidy details if subsidy_id is provided in URL
$selected_subsidy = null;
$subsidy_id = null;

if (isset($_GET['subsidy_id']) && !empty($_GET['subsidy_id'])) {
    $subsidy_id = $_GET['subsidy_id'];
    // Get subsidy details
    $subsidy_query = "SELECT * FROM subsidies WHERE id = ? AND status = 'active'";
    $subsidy_stmt = $conn->prepare($subsidy_query);
    $subsidy_stmt->bind_param("i", $subsidy_id);
    $subsidy_stmt->execute();
    $subsidy_result = $subsidy_stmt->get_result();
    
    if ($subsidy_result->num_rows > 0) {
        $selected_subsidy = $subsidy_result->fetch_assoc();
    } else {
        // Debug: Log the issue
        error_log("Subsidy not found: ID = " . $subsidy_id);
    }
}

// Handle form submission for new application
$success_message = "";
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_application'])) {
    $subsidy_id = $_POST['subsidy_id'];
    $full_name = $_POST['full_name'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $district = $_POST['district'];
    $state = $_POST['state'];
    $pincode = $_POST['pincode'];
    $land_holding = $_POST['land_holding'];
    $annual_income = $_POST['annual_income'];
    $bank_account_number = $_POST['bank_account_number'];
    $ifsc_code = $_POST['ifsc_code'];
    $bank_name = $_POST['bank_name'];
    
    // Create uploads directory if it doesn't exist
    $upload_dir = "uploads/";
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Function to handle file upload with size validation and compression
    function uploadFile($file, $upload_dir, $prefix) {
        if ($file['error'] == 0) {
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
            $file_name = $prefix . '_' . time() . '_' . uniqid() . '.' . $file_extension;
            $file_path = $upload_dir . $file_name;
            
            // Compress image if it's too large and GD is available
            if ($file['size'] > 1024 * 1024 && extension_loaded('gd')) { // If larger than 1MB and GD available
                $file_path = compressAndUploadImage($file['tmp_name'], $file_path, $file['type']);
            } else {
                if (!move_uploaded_file($file['tmp_name'], $file_path)) {
                    return "ERROR: Failed to upload file.";
                }
            }
            
            return $file_path;
        }
        return "ERROR: File upload failed.";
    }
    
    // Function to compress and upload image
    function compressAndUploadImage($source_path, $destination_path, $image_type) {
        // Check if GD library is available
        if (!extension_loaded('gd')) {
            // Fallback: just copy the file without compression
            if (copy($source_path, $destination_path)) {
                return $destination_path;
            } else {
                return "ERROR: Failed to copy file (GD not available).";
            }
        }
        
        $max_width = 1200;
        $max_height = 1200;
        $quality = 80;
        
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
    
    // Upload all documents
    $aadhar_image = uploadFile($_FILES['aadhar_card'], $upload_dir, 'aadhar');
    $pan_image = uploadFile($_FILES['pan_card'], $upload_dir, 'pan');
    $bank_passbook_image = uploadFile($_FILES['bank_passbook'], $upload_dir, 'bank');
    $land_documents_image = uploadFile($_FILES['land_documents'], $upload_dir, 'land');
    $income_certificate_image = uploadFile($_FILES['income_certificate'], $upload_dir, 'income');
    $caste_certificate_image = uploadFile($_FILES['caste_certificate'], $upload_dir, 'caste');
    $profile_photo_image = uploadFile($_FILES['profile_photo'], $upload_dir, 'profile');
    $signature_image = uploadFile($_FILES['signature'], $upload_dir, 'signature');
    
    // Check for upload errors
    $upload_errors = [];
    if (strpos($aadhar_image, 'ERROR:') === 0) $upload_errors[] = "Aadhar Card: " . $aadhar_image;
    if (strpos($pan_image, 'ERROR:') === 0) $upload_errors[] = "PAN Card: " . $pan_image;
    if (strpos($bank_passbook_image, 'ERROR:') === 0) $upload_errors[] = "Bank Passbook: " . $bank_passbook_image;
    if (strpos($land_documents_image, 'ERROR:') === 0) $upload_errors[] = "Land Documents: " . $land_documents_image;
    if (strpos($profile_photo_image, 'ERROR:') === 0) $upload_errors[] = "Profile Photo: " . $profile_photo_image;
    if (strpos($signature_image, 'ERROR:') === 0) $upload_errors[] = "Signature: " . $signature_image;
    
    // Optional documents
    if (!empty($_FILES['income_certificate']['name']) && strpos($income_certificate_image, 'ERROR:') === 0) {
        $upload_errors[] = "Income Certificate: " . $income_certificate_image;
    }
    if (!empty($_FILES['caste_certificate']['name']) && strpos($caste_certificate_image, 'ERROR:') === 0) {
        $upload_errors[] = "Caste Certificate: " . $caste_certificate_image;
    }
    
    if (!empty($upload_errors)) {
        $error_message = "Upload errors: " . implode(", ", $upload_errors);
    } else {
        // Check if user already applied for this subsidy
        $check_query = "SELECT id FROM user_applications WHERE user_email = ? AND subsidy_id = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("si", $user_email, $subsidy_id);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $error_message = "You have already applied for this subsidy scheme.";
        } else {
            // Insert application into database
            $insert_query = "INSERT INTO user_applications (
                user_email, subsidy_id, full_name, phone_number, address, district, state, pincode,
                land_holding, annual_income, bank_account_number, ifsc_code, bank_name,
                aadhar_card_image, pan_card_image, bank_passbook_image, land_documents_image,
                income_certificate_image, caste_certificate_image, profile_photo_image, signature_image
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("sissssssdssssssssssss", 
                $user_email, $subsidy_id, $full_name, $phone_number, $address, $district, $state, $pincode,
                $land_holding, $annual_income, $bank_account_number, $ifsc_code, $bank_name,
                $aadhar_image, $pan_image, $bank_passbook_image, $land_documents_image,
                $income_certificate_image, $caste_certificate_image, $profile_photo_image, $signature_image
            );
            
            if ($stmt->execute()) {
                $success_message = "Application submitted successfully! Your documents have been uploaded.";
            } else {
                $error_message = "Error submitting application: " . $conn->error;
            }
        }
    }
}

// Handle application deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_application_id'])) {
    $delete_id = intval($_POST['delete_application_id']);
    // Only allow deletion of user's own application
    $delete_stmt = $conn->prepare("DELETE FROM user_applications WHERE id = ? AND user_email = ?");
    $delete_stmt->bind_param("is", $delete_id, $user_email);
    if ($delete_stmt->execute()) {
        $success_message = "Application deleted successfully.";
    } else {
        $error_message = "Error deleting application: " . $conn->error;
    }
}

// Get user's applications with subsidy details
$query = "SELECT ua.*, s.title, s.amount, s.application_deadline, s.category, s.state 
          FROM user_applications ua 
          JOIN subsidies s ON ua.subsidy_id = s.id 
          WHERE ua.user_email = ? 
          ORDER BY ua.application_date DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$applications = $stmt->get_result();

// Get statistics
$total_applications = $applications->num_rows;
$pending_count = 0;
$approved_count = 0;
$rejected_count = 0;

$applications_array = [];
while ($app = $applications->fetch_assoc()) {
    $applications_array[] = $app;
    switch ($app['status']) {
        case 'pending':
            $pending_count++;
            break;
        case 'approved':
            $approved_count++;
            break;
        case 'rejected':
            $rejected_count++;
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Applications - AgriGrow</title>
    <link rel="icon" href="../photos/home/favicon2.svg" type="image/svg+xml">
    <link href="./output.css" rel="stylesheet">
    <link rel="stylesheet" href="./homecss.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .status-pending { background: linear-gradient(45deg, #f59e0b, #d97706); }
        .status-approved { background: linear-gradient(45deg, #10b981, #059669); }
        .status-rejected { background: linear-gradient(45deg, #ef4444, #dc2626); }
        .status-review { background: linear-gradient(45deg, #3b82f6, #2563eb); }
        
        .application-card {
            transition: all 0.3s ease;
            border: 1px solid #374151;
        }
        .application-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }
        
        .stats-card {
            background: linear-gradient(135deg, #4ade80, #3b82f6);
        }
    </style>
</head>
<body class="font-mono bg-gray-950 text-white relative">

<!-- Header -->
<?php include 'header.php'; ?>

<!-- Main Content -->
<div class="container mx-auto px-4 py-8">
    
    <!-- Hero Section -->
    <div class="text-center mb-12">
        <h1 class="text-5xl font-bold mb-4 text-lime-400">📋 My Applications</h1>
        <p class="text-xl text-gray-300 max-w-4xl mx-auto">
            Track the status of your subsidy applications and stay updated on your farming support journey.
        </p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="stats-card p-6 rounded-xl text-white">
            <div class="text-2xl font-bold"><?php echo $total_applications; ?></div>
            <div class="text-sm opacity-90">Total Applications</div>
        </div>
        <div class="bg-gray-800 p-6 rounded-xl">
            <div class="text-2xl font-bold text-yellow-400"><?php echo $pending_count; ?></div>
            <div class="text-sm text-gray-400">Pending Review</div>
        </div>
        <div class="bg-gray-800 p-6 rounded-xl">
            <div class="text-2xl font-bold text-green-400"><?php echo $approved_count; ?></div>
            <div class="text-sm text-gray-400">Approved</div>
        </div>
        <div class="bg-gray-800 p-6 rounded-xl">
            <div class="text-2xl font-bold text-red-400"><?php echo $rejected_count; ?></div>
            <div class="text-sm text-gray-400">Rejected</div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <?php if (!empty($success_message)): ?>
        <div class="mb-6 p-4 bg-green-500/20 border border-green-500 rounded-lg text-green-400">
            <?php echo htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($error_message)): ?>
        <div class="mb-6 p-4 bg-red-500/20 border border-red-500 rounded-lg text-red-400">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <!-- New Application Form -->
    <div class="bg-gray-800 rounded-xl p-6 mb-8">
        <h2 class="text-2xl font-bold mb-6 text-lime-400">📝 Apply for New Subsidy</h2>
        
        <?php if ($selected_subsidy): ?>
            <!-- Selected Subsidy Details -->
            <div class="bg-blue-900/20 border border-blue-500 rounded-lg p-4 mb-6">
                <h3 class="text-lg font-semibold text-blue-400 mb-2">Selected Scheme:</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-white font-medium"><?php echo htmlspecialchars($selected_subsidy['title']); ?></p>
                        <p class="text-gray-400 text-sm"><?php echo htmlspecialchars($selected_subsidy['description']); ?></p>
                    </div>
                    <div class="text-right">
                        <p class="text-lime-400 font-bold">
                            <?php 
                            // Handle amount display - check if it's numeric or text
                            if (is_numeric($selected_subsidy['amount'])) {
                                echo "₹" . number_format((float)$selected_subsidy['amount'], 2);
                            } else {
                                echo htmlspecialchars($selected_subsidy['amount']);
                            }
                            ?>
                        </p>
                        <!-- Deadline removed -->
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Debug info -->
            <div class="bg-yellow-900/20 border border-yellow-500 rounded-lg p-4 mb-6">
                <p class="text-yellow-400">Debug: No subsidy selected. URL parameters: <?php echo isset($_GET['subsidy_id']) ? $_GET['subsidy_id'] : 'none'; ?></p>
            </div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            <!-- Subsidy Selection -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <?php echo $selected_subsidy ? 'Subsidy Scheme' : 'Select Subsidy Scheme'; ?>
                    </label>
                    <?php if ($selected_subsidy): ?>
                        <!-- Hidden input for selected subsidy -->
                        <input type="hidden" name="subsidy_id" value="<?php echo $selected_subsidy['id']; ?>">
                        <input type="text" value="<?php echo htmlspecialchars($selected_subsidy['title']); ?>" readonly 
                               class="w-full px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg text-white cursor-not-allowed">
                    <?php else: ?>
                        <!-- Dropdown for subsidy selection -->
                        <select name="subsidy_id" required class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500">
                            <option value="">Choose a subsidy scheme...</option>
                            <?php
                            $subsidies_query = "SELECT * FROM subsidies WHERE status = 'active' ORDER BY title";
                            $subsidies_result = $conn->query($subsidies_query);
                            while ($subsidy = $subsidies_result->fetch_assoc()) {
                                echo "<option value='" . $subsidy['id'] . "'>" . htmlspecialchars($subsidy['title']) . "</option>";
                            }
                            ?>
                        </select>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Personal Details -->
            <div class="bg-gray-700 rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4 text-blue-400">👤 Personal Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Full Name *</label>
                        <input type="text" name="full_name" required class="w-full px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg text-white focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Phone Number *</label>
                        <input type="tel" name="phone_number" required class="w-full px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg text-white focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Address *</label>
                        <textarea name="address" required rows="3" class="w-full px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg text-white focus:outline-none focus:border-blue-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">District *</label>
                        <input type="text" name="district" required class="w-full px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg text-white focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">State *</label>
                        <input type="text" name="state" required class="w-full px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg text-white focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Pincode *</label>
                        <input type="text" name="pincode" required class="w-full px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg text-white focus:outline-none focus:border-blue-500">
                    </div>
                </div>
            </div>

            <!-- Land and Income Details -->
            <div class="bg-gray-700 rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4 text-green-400">🌾 Land & Income Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Land Holding (in acres) *</label>
                        <input type="number" name="land_holding" step="0.01" required class="w-full px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg text-white focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Annual Income (₹) *</label>
                        <input type="number" name="annual_income" step="0.01" required class="w-full px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg text-white focus:outline-none focus:border-blue-500">
                    </div>
                </div>
            </div>

            <!-- Bank Details -->
            <div class="bg-gray-700 rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4 text-purple-400">🏦 Bank Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Bank Account Number *</label>
                        <input type="text" name="bank_account_number" required class="w-full px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg text-white focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">IFSC Code *</label>
                        <input type="text" name="ifsc_code" required class="w-full px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg text-white focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Bank Name *</label>
                        <input type="text" name="bank_name" required class="w-full px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg text-white focus:outline-none focus:border-blue-500">
                    </div>
                </div>
            </div>

            <!-- Document Uploads -->
            <div class="bg-gray-700 rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4 text-yellow-400">📄 Required Documents</h3>
                <p class="text-sm text-gray-400 mb-4">📏 Maximum file size: 5MB per image. Supported formats: JPG, PNG, GIF</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Aadhar Card *</label>
                        <input type="file" name="aadhar_card" accept="image/*" required class="w-full px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg text-white focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">PAN Card *</label>
                        <input type="file" name="pan_card" accept="image/*" required class="w-full px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg text-white focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Bank Passbook *</label>
                        <input type="file" name="bank_passbook" accept="image/*" required class="w-full px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg text-white focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Land Documents *</label>
                        <input type="file" name="land_documents" accept="image/*" required class="w-full px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg text-white focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Income Certificate</label>
                        <input type="file" name="income_certificate" accept="image/*" class="w-full px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg text-white focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Caste Certificate</label>
                        <input type="file" name="caste_certificate" accept="image/*" class="w-full px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg text-white focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Profile Photo *</label>
                        <input type="file" name="profile_photo" accept="image/*" required class="w-full px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg text-white focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Signature *</label>
                        <input type="file" name="signature" accept="image/*" required class="w-full px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg text-white focus:outline-none focus:border-blue-500">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" name="submit_application" class="bg-lime-500 hover:bg-lime-600 text-white font-bold py-3 px-8 rounded-lg transition-colors duration-300">
                    <i class="fas fa-paper-plane mr-2"></i>
                    <?php echo $selected_subsidy ? 'Apply for ' . htmlspecialchars($selected_subsidy['title']) : 'Submit Application'; ?>
                </button>
            </div>
        </form>
    </div>

    <!-- Applications List -->
    <?php if (count($applications_array) > 0): ?>
        <div class="space-y-6">
            <?php foreach ($applications_array as $app): ?>
                <?php 
                $deadline = new DateTime($app['application_deadline']);
                $now = new DateTime();
                $is_expired = $deadline < $now;
                ?>
                
                <div class="application-card bg-gray-800 rounded-xl p-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between">
                        <!-- Application Details -->
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-4">
                                <h3 class="text-xl font-bold text-white">
                                    <?php echo htmlspecialchars($app['title']); ?>
                                </h3>
                                
                                <!-- Status Badge -->
                                <span class="px-3 py-1 rounded-full text-sm text-white font-medium
                                    <?php 
                                    switch ($app['status']) {
                                        case 'pending':
                                            echo 'status-pending';
                                            break;
                                        case 'approved':
                                            echo 'status-approved';
                                            break;
                                        case 'rejected':
                                            echo 'status-rejected';
                                            break;
                                        case 'under_review':
                                            echo 'status-review';
                                            break;
                                    }
                                    ?>">
                                    <?php 
                                    switch ($app['status']) {
                                        case 'pending':
                                            echo '⏳ Pending';
                                            break;
                                        case 'approved':
                                            echo '✅ Approved';
                                            break;
                                        case 'rejected':
                                            echo '❌ Rejected';
                                            break;
                                        case 'under_review':
                                            echo '🔍 Under Review';
                                            break;
                                    }
                                    ?>
                                </span>
                            </div>
                            
                            <!-- Key Information -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-money-bill-wave text-lime-400 mr-2"></i>
                                    <span class="text-gray-300"><?php echo htmlspecialchars($app['amount']); ?></span>
                                </div>
                                
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-map-marker-alt text-blue-400 mr-2"></i>
                                    <span class="text-gray-300"><?php echo htmlspecialchars($app['state']); ?></span>
                                </div>
                                
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-tag text-purple-400 mr-2"></i>
                                    <span class="text-gray-300"><?php echo htmlspecialchars($app['category']); ?></span>
                                </div>
                            </div>
                            
                            <!-- Application Timeline -->
                            <div class="space-y-2 text-sm text-gray-400">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-plus text-green-400 mr-2"></i>
                                    <span>Applied: <?php echo date('M j, Y', strtotime($app['application_date'])); ?></span>
                                </div>
                                
                                
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex flex-col gap-2 mt-4 md:mt-0 md:ml-6">
                            <?php if ($app['status'] === 'approved'): ?>
                                <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors">
                                    <i class="fas fa-download mr-2"></i>Download Certificate
                                </button>
                            <?php elseif ($app['status'] === 'pending' || $app['status'] === 'under_review'): ?>
                                
                            <?php endif; ?>
                            
                            
                            <form method="POST" onsubmit="return confirm('Are you sure you want to delete this application?');">
        <input type="hidden" name="delete_application_id" value="<?php echo $app['id']; ?>">
        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-trash mr-2"></i>Delete
        </button>
    </form>
                        </div>
                    </div>
                    
                    <!-- Notes Section (if any) -->
                    <?php if (!empty($app['notes'])): ?>
                        <div class="mt-4 p-3 bg-gray-700 rounded-lg">
                            <h4 class="text-sm font-semibold text-gray-300 mb-1">Notes:</h4>
                            <p class="text-sm text-gray-400"><?php echo htmlspecialchars($app['notes']); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!-- No Applications Message -->
        <div class="text-center py-12">
            <i class="fas fa-clipboard-list text-6xl text-gray-600 mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-400 mb-2">No applications yet</h3>
            <p class="text-gray-500 mb-6">Start your journey by applying for government subsidies.</p>
            <a href="dynamic_subsidies.php" class="bg-lime-500 hover:bg-lime-600 text-white px-6 py-3 rounded-lg transition-colors inline-block">
                <i class="fas fa-plus mr-2"></i>Browse Subsidies
            </a>
        </div>
    <?php endif; ?>
</div>

<!-- Footer -->
<footer class="bg-gray-900 mt-10 py-4">
    <div class="text-center text-gray-400">
        © 2025 AgriGrow. All rights reserved.
    </div>
</footer>

<script>
    // Profile dropdown toggle
    const menuBtn = document.getElementById('menu-btn');
    const profileMenu = document.getElementById('profile-menu');
    
    menuBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        profileMenu.classList.toggle('hidden');
    });
    
    document.addEventListener('click', () => {
        profileMenu.classList.add('hidden');
    });
</script>

</body>
</html>