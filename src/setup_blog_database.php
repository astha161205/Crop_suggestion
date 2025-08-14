<?php
// Blog Database Setup Script
// Run this script to create the blogs table and insert sample data

// Load environment variables from .env
require __DIR__ . '/../vendor/autoload.php'; // adjust path if needed
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Database connection using env vars
$host = $_ENV['MYSQL_HOST'];
$port = $_ENV['MYSQL_PORT'];
$dbname = $_ENV['MYSQL_DATABASE'];
$username = $_ENV['MYSQL_USER'];
$password = $_ENV['MYSQL_PASSWORD'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password, $port);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Blog Database Setup</h2>";
    
    // Create blogs table
    $sql = "CREATE TABLE IF NOT EXISTS blogs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(200) NOT NULL,
        cover_image_url VARCHAR(500) NOT NULL,
        content TEXT NOT NULL,
        tags VARCHAR(200),
        author_id INT,
        author_name VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        status ENUM('published', 'draft') DEFAULT 'published',
        FOREIGN KEY (author_id) REFERENCES farmer_profiles(id) ON DELETE SET NULL
    )";
    
    $pdo->exec($sql);
    echo "<p style='color: green;'>✓ Blogs table created successfully!</p>";
    
    // Check if sample data already exists
    $stmt = $pdo->query("SELECT COUNT(*) FROM blogs");
    $count = $stmt->fetchColumn();
    
    if ($count == 0) {
        // Insert sample blog data
        $sample_blogs = [
            [
                'title' => 'Revolutionizing Agriculture with IoT: A Comprehensive Guide',
                'cover_image_url' => '../photos/home/IoT-in-Agriculture.png',
                'content' => 'The integration of Internet of Things (IoT) technology in agriculture is transforming traditional farming practices. Smart sensors deployed across fields collect real-time data on soil moisture, temperature, and crop health, enabling farmers to make data-driven decisions.

Key IoT Applications:
• Precision irrigation systems reducing water usage by 40%
• Livestock monitoring through GPS-enabled collars
• Automated pest detection using image recognition
• Predictive analytics for crop yield optimization

Recent case studies from California\'s Central Valley show IoT adoption has increased average yields by 22% while reducing chemical usage by 35%. Farmers can now monitor their fields remotely through mobile apps, receiving instant alerts about potential issues.',
                'tags' => 'Smart Farming, IoT, Technology',
                'author_name' => 'AgriGrow Team'
            ],
            [
                'title' => 'Integrated Pest Management: Sustainable Solutions for Modern Farms',
                'cover_image_url' => '../photos/home/Pest-management.jpg',
                'content' => 'Traditional pesticide reliance is being replaced by Integrated Pest Management (IPM) strategies that combine biological, cultural, and mechanical controls. This approach reduces chemical use by 50-75% while maintaining crop protection.

Biological Controls:
• Ladybugs for aphid control
• Parasitic wasps against caterpillars
• Nematodes for soil-borne pests

Cultural Practices:
• Crop rotation strategies
• Trap cropping techniques
• Resistant variety selection

A 2023 USDA study showed farms implementing IPM strategies saw 28% higher profitability due to reduced input costs and premium organic pricing. Mobile apps like PestWeb now help farmers identify pests through AI-powered image recognition.',
                'tags' => 'Pest Control, IPM, Sustainable Farming',
                'author_name' => 'AgriGrow Team'
            ],
            [
                'title' => 'Drone Technology in Precision Agriculture: 2023 Market Report',
                'cover_image_url' => '../photos/home/Drone.jpg',
                'content' => 'Agricultural drone usage has skyrocketed 300% since 2020, with the global market expected to reach $9.6 billion by 2027. Modern drones equipped with multispectral sensors provide crucial data for:

Top Applications:
• Crop health monitoring
• Precision spraying
• Planting optimization

Key Benefits:
• 90% chemical reduction
• 60% faster field analysis
• $127/acre cost savings

New AI-powered drones like the DJI Agras T40 can autonomously map 500-acre fields in 90 minutes, while variable-rate spraying systems reduce herbicide use by targeting weeds with millimeter precision.',
                'tags' => 'Agri-Tech, Drones, Precision Agriculture',
                'author_name' => 'AgriGrow Team'
            ]
        ];
        
        $stmt = $pdo->prepare("INSERT INTO blogs (title, cover_image_url, content, tags, author_name, created_at) VALUES (?, ?, ?, ?, ?, ?)");
        
        foreach ($sample_blogs as $blog) {
            $stmt->execute([
                $blog['title'],
                $blog['cover_image_url'],
                $blog['content'],
                $blog['tags'],
                $blog['author_name'],
                date('Y-m-d H:i:s', strtotime('-' . rand(1, 30) . ' days'))
            ]);
        }
        
        echo "<p style='color: green;'>✓ Sample blog data inserted successfully!</p>";
    } else {
        echo "<p style='color: blue;'>ℹ Sample data already exists. Skipping insertion.</p>";
    }
    
    echo "<h3>Setup Complete!</h3>";
    echo "<p>Your blog system is now ready. You can:</p>";
    echo "<ul>";
    echo "<li><a href='blog.php'>View all blogs</a></li>";
    echo "<li><a href='write_blog.php'>Write a new blog</a></li>";
    echo "<li><a href='homePage.php'>Go to homepage</a></li>";
    echo "</ul>";
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Database Setup - Agri-Grow</title>
    <link rel="icon" href="../photos/home/favicon2.svg" type="image/svg+xml">
    <style>
        body {
            font-family: 'Courier New', monospace;
            background-color: #0f172a;
            color: white;
            padding: 20px;
            line-height: 1.6;
        }
        h2, h3 {
            color: #84cc16;
        }
        a {
            color: #84cc16;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        ul {
            margin-left: 20px;
        }
        li {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <!-- Content will be displayed above -->
</body>
</html>

