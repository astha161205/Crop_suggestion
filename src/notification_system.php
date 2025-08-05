<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "crop");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to create notification
function createNotification($conn, $user_email, $subsidy_id, $type, $message) {
    $query = "INSERT INTO subsidy_notifications (user_email, subsidy_id, notification_type, message) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("siss", $user_email, $subsidy_id, $type, $message);
    return $stmt->execute();
}

// Function to get user notifications
function getUserNotifications($conn, $user_email) {
    $query = "SELECT sn.*, s.title FROM subsidy_notifications sn 
              JOIN subsidies s ON sn.subsidy_id = s.id 
              WHERE sn.user_email = ? 
              ORDER BY sn.created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    return $stmt->get_result();
}

// Function to mark notification as read
function markNotificationAsRead($conn, $notification_id) {
    $query = "UPDATE subsidy_notifications SET is_read = TRUE WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $notification_id);
    return $stmt->execute();
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'mark_read' && isset($_POST['notification_id'])) {
        $notification_id = $_POST['notification_id'];
        if (markNotificationAsRead($conn, $notification_id)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to mark as read']);
        }
        exit();
    }
}

// Get notifications for logged-in user
$notifications = [];
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $notifications_result = getUserNotifications($conn, $_SESSION['user_email']);
    while ($notification = $notifications_result->fetch_assoc()) {
        $notifications[] = $notification;
    }
}

// Count unread notifications
$unread_count = 0;
foreach ($notifications as $notification) {
    if (!$notification['is_read']) {
        $unread_count++;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - AgriGrow</title>
    <link rel="icon" href="../photos/home/favicon2.svg" type="image/svg+xml">
    <link href="./output.css" rel="stylesheet">
    <link rel="stylesheet" href="./homecss.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .notification-card {
            transition: all 0.3s ease;
            border: 1px solid #374151;
        }
        .notification-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }
        .notification-unread {
            border-left: 4px solid #4ade80;
            background: rgba(74, 222, 128, 0.1);
        }
        .notification-read {
            opacity: 0.7;
        }
        .notification-badge {
            background: linear-gradient(45deg, #ef4444, #dc2626);
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
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
        <a href="./dynamic_subsidies.php" class="hover:text-white">Subsidies</a>
        <a href="./blog.php" class="hover:text-white">Blog</a>
        <a href="./homePage.php#About" class="hover:text-white">About us</a>
    </div>

    <div class="relative">
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
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
        <?php else: ?>
            <a href="./login.php" class="p-2 bg-lime-500 text-white rounded-lg hover:bg-lime-600 transition-colors">Login</a>
        <?php endif; ?>
    </div>
</header>

<!-- Main Content -->
<div class="container mx-auto px-4 py-8">
    
    <!-- Hero Section -->
    <div class="text-center mb-12">
        <h1 class="text-5xl font-bold mb-4 text-lime-400">🔔 Notifications</h1>
        <p class="text-xl text-gray-300 max-w-4xl mx-auto">
            Stay updated with the latest subsidy information, application status, and important deadlines.
        </p>
    </div>

    <!-- Notification Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gray-800 p-6 rounded-xl">
            <div class="text-2xl font-bold text-lime-400"><?php echo count($notifications); ?></div>
            <div class="text-sm text-gray-400">Total Notifications</div>
        </div>
        <div class="bg-gray-800 p-6 rounded-xl">
            <div class="text-2xl font-bold text-yellow-400"><?php echo $unread_count; ?></div>
            <div class="text-sm text-gray-400">Unread</div>
        </div>
        <div class="bg-gray-800 p-6 rounded-xl">
            <div class="text-2xl font-bold text-blue-400">Real-time</div>
            <div class="text-sm text-gray-400">Updates</div>
        </div>
    </div>

    <!-- Notifications List -->
    <?php if (count($notifications) > 0): ?>
        <div class="space-y-4">
            <?php foreach ($notifications as $notification): ?>
                <div class="notification-card bg-gray-800 rounded-xl p-6 <?php echo $notification['is_read'] ? 'notification-read' : 'notification-unread'; ?>">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <?php 
                                $icon = '';
                                $color = '';
                                switch ($notification['notification_type']) {
                                    case 'deadline':
                                        $icon = '⏰';
                                        $color = 'text-red-400';
                                        break;
                                    case 'new_subsidy':
                                        $icon = '🆕';
                                        $color = 'text-green-400';
                                        break;
                                    case 'status_update':
                                        $icon = '📊';
                                        $color = 'text-blue-400';
                                        break;
                                }
                                ?>
                                <span class="text-2xl"><?php echo $icon; ?></span>
                                <span class="px-3 py-1 rounded-full text-sm font-medium <?php echo $color; ?>">
                                    <?php 
                                    switch ($notification['notification_type']) {
                                        case 'deadline':
                                            echo 'Deadline Alert';
                                            break;
                                        case 'new_subsidy':
                                            echo 'New Subsidy';
                                            break;
                                        case 'status_update':
                                            echo 'Status Update';
                                            break;
                                    }
                                    ?>
                                </span>
                                
                                <?php if (!$notification['is_read']): ?>
                                    <span class="notification-badge w-3 h-3 rounded-full"></span>
                                <?php endif; ?>
                            </div>
                            
                            <h3 class="text-lg font-semibold text-white mb-2">
                                <?php echo htmlspecialchars($notification['title']); ?>
                            </h3>
                            
                            <p class="text-gray-300 mb-3">
                                <?php echo htmlspecialchars($notification['message']); ?>
                            </p>
                            
                            <div class="flex items-center text-sm text-gray-400">
                                <i class="fas fa-clock mr-2"></i>
                                <span><?php echo date('M j, Y g:i A', strtotime($notification['created_at'])); ?></span>
                            </div>
                        </div>
                        
                        <div class="flex flex-col gap-2 ml-4">
                            <?php if (!$notification['is_read']): ?>
                                <button onclick="markAsRead(<?php echo $notification['id']; ?>)" 
                                        class="bg-lime-500 hover:bg-lime-600 text-white px-3 py-1 rounded text-sm transition-colors">
                                    <i class="fas fa-check mr-1"></i>Mark Read
                                </button>
                            <?php endif; ?>
                            
                            <button class="bg-gray-700 hover:bg-gray-600 text-white px-3 py-1 rounded text-sm transition-colors">
                                <i class="fas fa-eye mr-1"></i>View Details
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!-- No Notifications Message -->
        <div class="text-center py-12">
            <i class="fas fa-bell-slash text-6xl text-gray-600 mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-400 mb-2">No notifications yet</h3>
            <p class="text-gray-500">You'll receive notifications about subsidy updates, deadlines, and application status here.</p>
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

    // Mark notification as read
    function markAsRead(notificationId) {
        fetch('notification_system.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=mark_read&notification_id=${notificationId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload page to update UI
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // Auto-refresh notifications every 30 seconds
    setInterval(() => {
        // You can implement AJAX call to check for new notifications
        // and update the notification count without full page reload
    }, 30000);
</script>

</body>
</html> 