<?php
session_start();
require_once 'theme_manager.php';
$theme = getThemeClasses();

// Database connection
$conn = new mysqli("localhost", "root", "", "crop");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get filters from URL parameters
$category_filter = $_GET['category'] ?? '';
$state_filter = $_GET['state'] ?? '';
$search_query = $_GET['search'] ?? '';

// Build query with filters
$query = "SELECT * FROM subsidies WHERE status = 'active'";
$params = [];
$types = "";

if (!empty($category_filter)) {
    $query .= " AND category = ?";
    $params[] = $category_filter;
    $types .= "s";
}

if (!empty($state_filter)) {
    $query .= " AND state = ?";
    $params[] = $state_filter;
    $types .= "s";
}

if (!empty($search_query)) {
    $query .= " AND (title LIKE ? OR description LIKE ?)";
    $params[] = "%$search_query%";
    $params[] = "%$search_query%";
    $types .= "ss";
}

$query .= " ORDER BY application_deadline ASC";

// Prepare and execute query
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Get categories for filter
$categories_query = "SELECT DISTINCT category FROM subsidies WHERE status = 'active'";
$categories_result = $conn->query($categories_query);

// Get states for filter
$states_query = "SELECT DISTINCT state FROM subsidies WHERE status = 'active'";
$states_result = $conn->query($states_query);

// Handle application submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['apply_subsidy'])) {
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        $subsidy_id = $_POST['subsidy_id'];
        $user_email = $_SESSION['user_email'];
        
        // Check if already applied
        $check_query = "SELECT * FROM user_applications WHERE user_email = ? AND subsidy_id = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("si", $user_email, $subsidy_id);
        $check_stmt->execute();
        
        if ($check_stmt->get_result()->num_rows == 0) {
            // Insert application
            $insert_query = "INSERT INTO user_applications (user_email, subsidy_id) VALUES (?, ?)";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param("si", $user_email, $subsidy_id);
            
            if ($insert_stmt->execute()) {
                $success_message = "Application submitted successfully!";
            } else {
                $error_message = "Error submitting application.";
            }
        } else {
            $error_message = "You have already applied for this subsidy.";
        }
    } else {
        $error_message = "Please login to apply for subsidies.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Subsidies - AgriGrow</title>
    <link rel="icon" href="../photos/home/favicon2.svg" type="image/svg+xml">
    <link href="./output.css" rel="stylesheet">
    <link rel="stylesheet" href="./homecss.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .subsidy-card {
            transition: all 0.3s ease;
            border: 1px solid #374151;
        }
        .subsidy-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            border-color: #4ade80;
        }
        .deadline-warning {
            background: linear-gradient(45deg, #f59e0b, #d97706);
        }
        .deadline-urgent {
            background: linear-gradient(45deg, #ef4444, #dc2626);
        }
        .filter-section {
            backdrop-filter: blur(10px);
            background: rgba(31, 41, 55, 0.8);
        }
        .search-box {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        .stats-card {
            background: linear-gradient(135deg, #4ade80, #3b82f6);
        }
    </style>
</head>
<body class="font-mono <?php echo $theme['bg']; ?> <?php echo $theme['text']; ?> relative">

<!-- Header -->
<header class="flex justify-between items-center <?php echo $theme['bg_header']; ?> h-15 sticky z-20 border-b-2 <?php echo $theme['border_header']; ?> top-0 pl-3 pr-3">
    <div class="flex gap-2 items-center">
        <a href="./homePage.php" class="flex items-center gap-2">
            <img src="../photos/home/logo.png" alt="logo" class="h-10 w-10 rounded-4xl">
            <h3 class="">AgriGrow</h3>
        </a>
    </div>

    <div class="<?php echo $theme['text_secondary']; ?> flex gap-6 pl-0 pr-4 pt-1 pb-1 ml-auto">
        <a href="./homePage.php" class="<?php echo $theme['hover']; ?>">Home</a>
        <a href="./SUNSIDIES.php" class="<?php echo $theme['hover']; ?>">Subsidies</a>
        <a href="./blog.php" class="<?php echo $theme['hover']; ?>">Blog</a>
        <a href="./homePage.php#About" class="<?php echo $theme['hover']; ?>">About us</a>
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
            <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
                <a href="./admin_subsidies.php" class="<?php echo $theme['hover']; ?>">Admin Panel</a>
                <a href="./logout.php" class="<?php echo $theme['hover']; ?> text-red-400">Logout</a>
            <?php else: ?>
                <a href="./profile.php" class="<?php echo $theme['hover']; ?>">Profile</a>
            <?php endif; ?>
        <?php else: ?>
            <a href="./login.php" class="<?php echo $theme['hover']; ?>">Login</a>
        <?php endif; ?>
    </div>
</header>
        
         
<!-- Main Content -->
<div class="max-w-6xl mx-auto px-4 py-8">
    
    <!-- Success/Error Messages -->
    <?php if (isset($success_message)): ?>
        <div class="mb-6 p-4 bg-green-500/20 border border-green-500 rounded-lg text-green-400">
            <?php echo htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($error_message)): ?>
        <div class="mb-6 p-4 bg-red-500/20 border border-red-500 rounded-lg text-red-400">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <!-- Hero Section -->
    <div class="text-center mb-12">
        <h1 class="text-5xl font-bold mb-6 text-lime-400">🌾 Government Subsidies</h1>
        <p class="text-xl text-gray-300 max-w-4xl mb-5 mx-auto">
            Discover and apply for government agricultural subsidies tailored to your needs. 
            Filter by category, state, and find the perfect support for your farming journey.
        </p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 max-w-5xl mx-auto">
        <div class="stats-card p-6 mt-4 rounded-xl text-white">
            <div class="text-2xl font-bold"><?php echo $result->num_rows; ?></div>
            <div class="text-sm opacity-90">Active Schemes</div>
        </div>
        
        <div class="bg-gray-800 p-6 rounded-xl">
            <div class="text-2xl font-bold text-blue-400">All India</div>
            <div class="text-sm text-gray-400">Coverage</div>
        </div>
        <div class="bg-gray-800 p-6 rounded-xl">
            <div class="text-2xl font-bold text-purple-400">24/7</div>
            <div class="text-sm text-gray-400">Support</div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="filter-section p-6 rounded-xl mb-8 max-w-5xl mx-auto">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search Box -->
                <div class="md:col-span-2">
                    <input type="text" name="search" placeholder="Search subsidies..." 
                           value="<?php echo htmlspecialchars($search_query); ?>"
                           class="w-full px-4 py-3 rounded-lg search-box text-white border border-gray-600 focus:outline-none focus:border-lime-500">
                </div>
                
                <!-- Category Filter -->
                <div>
                    <select name="category" class="w-full px-4 py-3 rounded-lg bg-gray-800 text-white border border-gray-600 focus:outline-none focus:border-lime-500">
                        <option value="">All Categories</option>
                        <?php while ($cat = $categories_result->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($cat['category']); ?>" 
                                    <?php echo $category_filter === $cat['category'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['category']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <!-- State Filter -->
                <div>
                    <select name="state" class="w-full px-4 py-3 rounded-lg bg-gray-800 text-white border border-gray-600 focus:outline-none focus:border-lime-500">
                        <option value="">All States</option>
                        <?php while ($state = $states_result->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($state['state']); ?>"
                                    <?php echo $state_filter === $state['state'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($state['state']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            
            <div class="flex justify-between items-center">
                <button type="submit" class="bg-lime-500 hover:bg-lime-600 text-white px-6 py-2 rounded-lg transition-colors">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
                
                <a href="SUNSIDIES.php" class="text-gray-400 hover:text-white transition-colors">
                    <i class="fas fa-times mr-2"></i>Clear Filters
                </a>
            </div>
        </form>
    </div>

    <!-- Subsidies Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-5xl mx-auto">
        <?php 
        $displayed_titles = array(); // Track displayed titles to avoid duplicates
        while ($subsidy = $result->fetch_assoc()): 
            // Skip if we've already displayed this title
            if (in_array($subsidy['title'], $displayed_titles)) {
                continue;
            }
            $displayed_titles[] = $subsidy['title'];
        ?>
            <div class="subsidy-card bg-gray-800 rounded-xl p-6">
                <!-- Status Badge -->
                <div class="flex justify-between items-start mb-4">
                    <span class="bg-lime-500/20 text-lime-400 px-3 py-1 rounded-full text-sm">
                        <?php echo htmlspecialchars($subsidy['category']); ?>
                    </span>
                    
                    <span class="bg-blue-500/20 text-blue-400 px-3 py-1 rounded-full text-sm">
                        Active
                    </span>
                </div>

                <!-- Title and Description -->
                <h3 class="text-xl font-bold mb-3 text-white">
                    <?php echo htmlspecialchars($subsidy['title']); ?>
                </h3>
                
                <p class="text-gray-300 mb-4 line-clamp-3">
                    <?php echo htmlspecialchars($subsidy['description']); ?>
                </p>

                <!-- Key Details -->
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm">
                        <i class="fas fa-money-bill-wave text-lime-400 mr-2"></i>
                        <span class="text-gray-300"><?php echo htmlspecialchars($subsidy['amount']); ?></span>
                    </div>
                    
                    <div class="flex items-center text-sm">
                        <i class="fas fa-map-marker-alt text-blue-400 mr-2"></i>
                        <span class="text-gray-300"><?php echo htmlspecialchars($subsidy['state']); ?></span>
                    </div>
                </div>

                <!-- Eligibility -->
                <div class="mb-4">
                    <h4 class="text-sm font-semibold text-gray-400 mb-2">Eligibility:</h4>
                    <p class="text-sm text-gray-300">
                        <?php echo htmlspecialchars($subsidy['eligibility_criteria']); ?>
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    <form method="POST" class="flex-1">
                        <input type="hidden" name="subsidy_id" value="<?php echo $subsidy['id']; ?>">
                        <button type="submit" name="apply_subsidy" 
                                class="w-full bg-lime-500 hover:bg-lime-600 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-paper-plane mr-2"></i>Apply Now
                        </button>
                    </form>
                    
                    <a href="<?php echo htmlspecialchars($subsidy['website_url']); ?>" 
                       target="_blank"
                       class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- No Results Message -->
    <?php if ($result->num_rows == 0): ?>
        <div class="text-center py-12">
            <i class="fas fa-search text-6xl text-gray-600 mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-400 mb-2">No subsidies found</h3>
            <p class="text-gray-500">Try adjusting your search criteria or filters.</p>
        </div>
    <?php endif; ?>
</div>




<!-- Footer -->
<footer class="bg-gray-900 mt-10 py-4">
    <div class="text-center text-gray-400">
        © 2025 AgriGrow. All rights reserved.
    </div>
</footer>

<script src="./theme.js"></script>
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