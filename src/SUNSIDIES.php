<?php
session_start();


require __DIR__ . '/../vendor/autoload.php'; // adjust path if needed

// Only load .env if it exists (prevents fatal error in production)
$dotenvPath = __DIR__ . '/../.env';
if (file_exists($dotenvPath)) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
}

require_once 'theme_manager.php';
$theme = getThemeClasses();

$host = getenv('MYSQL_HOST') ?: 'localhost';
$port = getenv('MYSQL_PORT') ?: '3306';
$dbname = getenv('MYSQL_DATABASE') ?: 'crop';
$username = getenv('MYSQL_USER') ?: 'root';
$password = getenv('MYSQL_PASSWORD') ?: '';
// $conn = new mysqli($host, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check connection health and reconnect if necessary
if ($conn->ping() === false) {
    $conn->close();
    $conn = new mysqli(
       $host, $username, $password, $dbname, $port
);
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

// Note: Application submission is now handled in my_applications.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Subsidies - AgriGrow</title>
    <link rel="icon" href="./home/favicon2.svg" type="image/svg+xml">
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
<?php include 'header.php'; ?>
         
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
            <div class="subsidy-card  <?php echo $theme['bg_card']; ?> rounded-xl p-6">
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
                
                <p class="text-gray-500 mb-4 line-clamp-3">
                    <?php echo htmlspecialchars($subsidy['description']); ?>
                </p>

                <!-- Key Details -->
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm">
                        <i class="fas fa-money-bill-wave text-lime-400 mr-2"></i>
                        <span class="text-gray-500"><?php echo htmlspecialchars($subsidy['amount']); ?></span>
                    </div>
                    
                    <div class="flex items-center text-sm">
                        <i class="fas fa-map-marker-alt text-blue-400 mr-2"></i>
                        <span class="text-gray-500"><?php echo htmlspecialchars($subsidy['state']); ?></span>
                    </div>
                </div>

                <!-- Eligibility -->
                <div class="mb-4">
                    <h4 class="text-sm font-semibold text-gray-400 mb-2">Eligibility:</h4>
                    <p class="text-sm text-gray-500">
                        <?php echo htmlspecialchars($subsidy['eligibility_criteria']); ?>
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    <a href="my_applications.php?subsidy_id=<?php echo $subsidy['id']; ?>" 
                       class="flex-1 bg-lime-500 hover:bg-lime-600 text-white px-4 py-2 rounded-lg transition-colors text-center">
                        <i class="fas fa-paper-plane mr-2"></i>Apply Now
                    </a>
                    
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