<?php
session_start();

// Check if user is logged in and is admin (you can add admin check logic)
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "crop");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_subsidy'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $eligibility = $_POST['eligibility_criteria'];
        $amount = $_POST['amount'];
        $deadline = $_POST['application_deadline'];
        $category = $_POST['category'];
        $state = $_POST['state'];
        $website_url = $_POST['website_url'];
        $requirements = $_POST['requirements'];
        
        $query = "INSERT INTO subsidies (title, description, eligibility_criteria, amount, application_deadline, category, state, website_url, requirements) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssssss", $title, $description, $eligibility, $amount, $deadline, $category, $state, $website_url, $requirements);
        
        if ($stmt->execute()) {
            $message = "Subsidy added successfully!";
        } else {
            $message = "Error adding subsidy: " . $conn->error;
        }
    }
    
    if (isset($_POST['update_status'])) {
        $subsidy_id = $_POST['subsidy_id'];
        $status = $_POST['status'];
        
        $query = "UPDATE subsidies SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $status, $subsidy_id);
        
        if ($stmt->execute()) {
            $message = "Status updated successfully!";
        } else {
            $message = "Error updating status: " . $conn->error;
        }
    }
}

// Get all subsidies
$subsidies_query = "SELECT * FROM subsidies ORDER BY created_at DESC";
$subsidies_result = $conn->query($subsidies_query);

// Get applications for review
$applications_query = "SELECT ua.*, s.title, ua.user_email FROM user_applications ua 
                      JOIN subsidies s ON ua.subsidy_id = s.id 
                      WHERE ua.status = 'pending' 
                      ORDER BY ua.application_date DESC";
$applications_result = $conn->query($applications_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Subsidies Management</title>
    <link rel="icon" href="../photos/home/favicon2.svg" type="image/svg+xml">
    <link href="./output.css" rel="stylesheet">
    <link rel="stylesheet" href="./homecss.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .admin-card {
            transition: all 0.3s ease;
            border: 1px solid #374151;
        }
        .admin-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }
        .status-active { background: linear-gradient(45deg, #10b981, #059669); }
        .status-inactive { background: linear-gradient(45deg, #ef4444, #dc2626); }
        .status-upcoming { background: linear-gradient(45deg, #3b82f6, #2563eb); }
    </style>
</head>
<body class="font-mono bg-gray-950 text-white relative">

<!-- Header -->
<header class="flex justify-between items-center bg-gray-950 h-15 sticky z-20 border-b-2 border-b-gray-900 top-0 pl-3 pr-3">
    <div class="flex gap-2 items-center">
        <a href="./index.php" class="flex items-center gap-2">
            <img src="../photos/home/logo.png" alt="logo" class="h-10 w-10 rounded-4xl">
            <h3 class="">AgriGrow Admin</h3>
        </a>
    </div>

    <div class="text-gray-400 flex items-center gap-5 border-2 border-gray-800 rounded-2xl pl-4 pr-4 pt-1 pb-1">
        <a href="./index.php" class="hover:text-white">Home</a>
        <a href="./SUNSIDIES.php" class="hover:text-white">Subsidies</a>
        <a href="./admin_subsidies.php" class="hover:text-white">Admin</a>
        <a href="./logout.php" class="hover:text-white text-red-400">Logout</a>
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
    
    <!-- Message Display -->
    <?php if (!empty($message)): ?>
        <div class="mb-6 p-4 bg-green-500/20 border border-green-500 rounded-lg text-green-400">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- Hero Section -->
    <div class="text-center mb-12">
        <h1 class="text-5xl font-bold mb-4 text-lime-400">⚙️ Admin Dashboard</h1>
        <p class="text-xl text-gray-300 max-w-4xl mx-auto">
            Manage subsidies, review applications, and monitor the agricultural support system.
        </p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gray-800 p-6 rounded-xl">
            <div class="text-2xl font-bold text-lime-400"><?php echo $subsidies_result->num_rows; ?></div>
            <div class="text-sm text-gray-400">Total Subsidies</div>
        </div>
        <div class="bg-gray-800 p-6 rounded-xl">
            <div class="text-2xl font-bold text-yellow-400"><?php echo $applications_result->num_rows; ?></div>
            <div class="text-sm text-gray-400">Pending Applications</div>
        </div>
        <div class="bg-gray-800 p-6 rounded-xl">
            <div class="text-2xl font-bold text-blue-400">Active</div>
            <div class="text-sm text-gray-400">System Status</div>
        </div>
        <div class="bg-gray-800 p-6 rounded-xl">
            <div class="text-2xl font-bold text-purple-400">24/7</div>
            <div class="text-sm text-gray-400">Monitoring</div>
        </div>
    </div>

    <!-- Add New Subsidy Form -->
    <div class="bg-gray-800 rounded-xl p-6 mb-8">
        <h2 class="text-2xl font-bold mb-6 text-lime-400">➕ Add New Subsidy</h2>
        <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Title</label>
                <input type="text" name="title" required class="w-full px-4 py-2 rounded-lg bg-gray-700 text-white border border-gray-600 focus:outline-none focus:border-lime-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Category</label>
                <select name="category" required class="w-full px-4 py-2 rounded-lg bg-gray-700 text-white border border-gray-600 focus:outline-none focus:border-lime-500">
                    <option value="">Select Category</option>
                    <option value="Income Support">Income Support</option>
                    <option value="Crop Insurance">Crop Insurance</option>
                    <option value="Equipment Subsidies">Equipment Subsidies</option>
                    <option value="Technology Adoption">Technology Adoption</option>
                    <option value="Organic Farming">Organic Farming</option>
                    <option value="Water Management">Water Management</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Amount</label>
                <input type="text" name="amount" required class="w-full px-4 py-2 rounded-lg bg-gray-700 text-white border border-gray-600 focus:outline-none focus:border-lime-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">State</label>
                <input type="text" name="state" required class="w-full px-4 py-2 rounded-lg bg-gray-700 text-white border border-gray-600 focus:outline-none focus:border-lime-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Application Deadline</label>
                <input type="date" name="application_deadline" required class="w-full px-4 py-2 rounded-lg bg-gray-700 text-white border border-gray-600 focus:outline-none focus:border-lime-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Website URL</label>
                <input type="url" name="website_url" required class="w-full px-4 py-2 rounded-lg bg-gray-700 text-white border border-gray-600 focus:outline-none focus:border-lime-500">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                <textarea name="description" rows="3" required class="w-full px-4 py-2 rounded-lg bg-gray-700 text-white border border-gray-600 focus:outline-none focus:border-lime-500"></textarea>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-300 mb-2">Eligibility Criteria</label>
                <textarea name="eligibility_criteria" rows="3" required class="w-full px-4 py-2 rounded-lg bg-gray-700 text-white border border-gray-600 focus:outline-none focus:border-lime-500"></textarea>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-300 mb-2">Requirements</label>
                <textarea name="requirements" rows="3" required class="w-full px-4 py-2 rounded-lg bg-gray-700 text-white border border-gray-600 focus:outline-none focus:border-lime-500"></textarea>
            </div>
            
            <div class="md:col-span-2">
                <button type="submit" name="add_subsidy" class="bg-lime-500 hover:bg-lime-600 text-white px-6 py-3 rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>Add Subsidy
                </button>
            </div>
        </form>
    </div>

    <!-- Subsidies Management -->
    <div class="bg-gray-800 rounded-xl p-6 mb-8">
        <h2 class="text-2xl font-bold mb-6 text-lime-400">📋 Manage Subsidies</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-700">
                        <th class="text-left py-3 px-4">Title</th>
                        <th class="text-left py-3 px-4">Category</th>
                        <th class="text-left py-3 px-4">Amount</th>
                        <th class="text-left py-3 px-4">Status</th>
                        <th class="text-left py-3 px-4">Deadline</th>
                        <th class="text-left py-3 px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($subsidy = $subsidies_result->fetch_assoc()): ?>
                        <tr class="border-b border-gray-700 hover:bg-gray-700">
                            <td class="py-3 px-4"><?php echo htmlspecialchars($subsidy['title']); ?></td>
                            <td class="py-3 px-4"><?php echo htmlspecialchars($subsidy['category']); ?></td>
                            <td class="py-3 px-4"><?php echo htmlspecialchars($subsidy['amount']); ?></td>
                            <td class="py-3 px-4">
                                <form method="POST" class="inline">
                                    <input type="hidden" name="subsidy_id" value="<?php echo $subsidy['id']; ?>">
                                    <select name="status" onchange="this.form.submit()" class="bg-gray-700 text-white px-2 py-1 rounded text-xs">
                                        <option value="active" <?php echo $subsidy['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                                        <option value="inactive" <?php echo $subsidy['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                        <option value="upcoming" <?php echo $subsidy['status'] === 'upcoming' ? 'selected' : ''; ?>>Upcoming</option>
                                    </select>
                                    <input type="hidden" name="update_status" value="1">
                                </form>
                            </td>
                            <td class="py-3 px-4"><?php echo date('M j, Y', strtotime($subsidy['application_deadline'])); ?></td>
                            <td class="py-3 px-4">
                                <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs mr-2">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Applications Review -->
    <div class="bg-gray-800 rounded-xl p-6">
        <h2 class="text-2xl font-bold mb-6 text-lime-400">📝 Review Applications</h2>
        <?php if ($applications_result->num_rows > 0): ?>
            <div class="space-y-4">
                <?php while ($app = $applications_result->fetch_assoc()): ?>
                    <div class="admin-card bg-gray-700 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-white"><?php echo htmlspecialchars($app['title']); ?></h3>
                                <p class="text-gray-300 text-sm">Applicant: <?php echo htmlspecialchars($app['user_email']); ?></p>
                                <p class="text-gray-400 text-sm">Applied: <?php echo date('M j, Y', strtotime($app['application_date'])); ?></p>
                            </div>
                            <div class="flex gap-2">
                                <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm">
                                    <i class="fas fa-check mr-1"></i>Approve
                                </button>
                                <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm">
                                    <i class="fas fa-times mr-1"></i>Reject
                                </button>
                                <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                                    <i class="fas fa-eye mr-1"></i>View
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-8">
                <i class="fas fa-clipboard-check text-4xl text-gray-600 mb-4"></i>
                <p class="text-gray-400">No pending applications to review.</p>
            </div>
        <?php endif; ?>
    </div>
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