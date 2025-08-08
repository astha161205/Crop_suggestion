<?php
session_start();

// Database connection
$host = 'localhost';
$dbname = 'crop';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Get blog ID from URL
$blog_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($blog_id <= 0) {
    header('Location: blog.php');
    exit();
}

// Fetch blog data
try {
    $stmt = $pdo->prepare("SELECT * FROM blogs WHERE id = ? AND status = 'published'");
    $stmt->execute([$blog_id]);
    $blog = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$blog) {
        header('Location: blog.php');
        exit();
    }
} catch(PDOException $e) {
    die("Error fetching blog: " . $e->getMessage());
}

// Format date
$formatted_date = date('F j, Y', strtotime($blog['created_at']));

// Convert content line breaks to HTML
$content = nl2br(htmlspecialchars($blog['content']));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($blog['title']); ?> - Agri-Grow</title>
    <link rel="icon" href="../photos/home/favicon2.svg" type="image/svg+xml">
    <link href="./output.css" rel="stylesheet">
    <link rel="stylesheet" href="./homecss.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="font-mono bg-gray-950 text-white relative">

<header class="flex justify-between items-center bg-gray-950 h-15 sticky z-20 border-b-2 border-b-gray-900 top-0 pl-3 pr-3">
    <div class="flex gap-2 items-center">
      <a href="./homePage.php" class="flex items-center gap-2">
        <img src="../photos/home/logo.png" alt="logo" class="h-10 w-10 rounded-4xl">
        <h3>AgriGrow</h3>
      </a>
    </div>
    <div class="text-gray-400 flex gap-6 pl-5 pr-4 pt-1 pb-1 ml-auto">
      <a href="./homePage.php" class="hover:text-white">Home</a>
      <a href="./SUNSIDIES.php" class="hover:text-white">Subsidies</a>
      <a href="./blog.php" class="hover:text-white">Blog</a>
      
      <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
        <a href="./profile.php" class="hover:text-white">Profile</a>
      <?php else: ?>
        <a href="./login.php" class="hover:text-white">Login</a>
      <?php endif; ?>
    </div>
</header>

<main class="container mx-auto px-4 py-12 max-w-4xl">
    <article class="bg-gray-900 rounded-2xl p-8">
        <!-- Back to blogs button -->
        <div class="mb-6">
            <a href="./blog.php" class="inline-flex items-center text-lime-400 hover:text-lime-300 transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to All Blogs
            </a>
        </div>

        <!-- Blog header -->
        <div class="flex items-center text-sm text-gray-400 mb-4">
            <?php if (!empty($blog['tags'])): ?>
                <span class="bg-lime-400/20 text-lime-300 px-3 py-1 rounded-full mr-4">
                    <?php echo htmlspecialchars(explode(',', $blog['tags'])[0]); ?>
                </span>
            <?php endif; ?>
            <span><?php echo $formatted_date; ?></span>
            <?php if (!empty($blog['author_name'])): ?>
                <span class="ml-4">by <?php echo htmlspecialchars($blog['author_name']); ?></span>
            <?php endif; ?>
        </div>

        <!-- Blog title -->
        <h1 class="text-3xl font-bold mb-6"><?php echo htmlspecialchars($blog['title']); ?></h1>

        <!-- Cover image -->
        <img src="<?php echo htmlspecialchars($blog['cover_image_url']); ?>" 
             alt="<?php echo htmlspecialchars($blog['title']); ?>" 
             class="w-full h-96 object-cover rounded-xl mb-8">

        <!-- Blog content -->
        <div class="space-y-6 text-gray-300">
            <?php echo $content; ?>
        </div>

        <!-- Tags -->
        <?php if (!empty($blog['tags'])): ?>
            <div class="mt-8 pt-6 border-t border-gray-700">
                <h3 class="text-lg font-bold text-lime-300 mb-3">Tags:</h3>
                <div class="flex flex-wrap gap-2">
                    <?php 
                    $tags = array_map('trim', explode(',', $blog['tags']));
                    foreach ($tags as $tag): 
                    ?>
                        <span class="bg-gray-800 text-gray-300 px-3 py-1 rounded-full text-sm">
                            <?php echo htmlspecialchars($tag); ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Share section -->
        <div class="mt-8 pt-6 border-t border-gray-700">
            <h3 class="text-lg font-bold text-lime-300 mb-3">Share this blog:</h3>
            <div class="flex gap-4">
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" 
                   target="_blank" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-300">
                    <i class="fab fa-facebook-f mr-2"></i>Facebook
                </a>
                <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($blog['title']); ?>&url=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" 
                   target="_blank" 
                   class="bg-blue-400 hover:bg-blue-500 text-white px-4 py-2 rounded-lg transition duration-300">
                    <i class="fab fa-twitter mr-2"></i>Twitter
                </a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" 
                   target="_blank" 
                   class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-lg transition duration-300">
                    <i class="fab fa-linkedin-in mr-2"></i>LinkedIn
                </a>
            </div>
        </div>
    </article>
</main>

<footer class="bg-gray-900 mt-5 w-full">
    <div class="flex justify-center items-center">
        <p>© 2021 AgriGrow. All rights reserved</p>
    </div>
</footer>

</body>
</html>
