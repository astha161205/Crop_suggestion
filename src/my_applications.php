<?php
session_start();

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

$user_email = $_SESSION['user_email'];

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
<header class="flex justify-between items-center bg-gray-950 h-15 sticky z-20 border-b-2 border-b-gray-900 top-0 pl-3 pr-3">
    <div class="flex gap-2 items-center">
        <a href="./homePage.php" class="flex items-center gap-2">
            <img src="../photos/home/logo.png" alt="logo" class="h-10 w-10 rounded-4xl">
            <h3 class="">AgriGrow</h3>
        </a>
    </div>

    <div class="text-gray-400 flex items-center gap-5 border-2 border-gray-800 rounded-2xl pl-4 pr-4 pt-1 pb-1">
        <a href="./homePage.php" class="hover:text-white">Home</a>
        <a href="./SUNSIDIES.php" class="hover:text-white">Subsidies</a>
        <a href="./blog.php" class="hover:text-white">Blog</a>
        <a href="./homePage.php#About" class="hover:text-white">About us</a>
        <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
            <a href="./logout.php" class="hover:text-white text-red-400">Logout</a>
        <?php endif; ?>
    </div>

    <div class="relative">
        <div class="flex items-center gap-2">
            <button id="menu-btn" class="p-2 hover:bg-gray-800 rounded-lg transition-colors flex items-center gap-2">
                <span class="text-white"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <i class="fa-solid fa-caret-down text-white text-sm"></i>
            </button>
        
            <div id="profile-menu" class="hidden absolute right-0 mt-20 w-48 bg-gray-800 rounded-lg shadow-xl py-2">
                <span class="block px-4 py-2 text-gray-400 cursor-default"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <a href="./logout.php" class="block px-4 py-2 text-white hover:bg-gray-700">Logout</a>
                <a href="./profile.php" class="block px-4 py-2 text-white hover:bg-gray-700">Profile</a>
            </div>
        </div>
    </div>
</header>

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
                                
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt text-blue-400 mr-2"></i>
                                    <span>Deadline: <?php echo date('M j, Y', strtotime($app['application_deadline'])); ?></span>
                                    <?php if ($is_expired): ?>
                                        <span class="ml-2 text-red-400">(Expired)</span>
                                    <?php endif; ?>
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
                                <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                                    <i class="fas fa-eye mr-2"></i>Track Progress
                                </button>
                            <?php endif; ?>
                            
                            <button class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="fas fa-info-circle mr-2"></i>View Details
                            </button>
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