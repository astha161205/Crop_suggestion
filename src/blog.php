<?php
session_start(); // Start the session

require __DIR__ . '/../vendor/autoload.php'; // adjust path if needed
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Database connection
$host = $_ENV['MYSQL_HOST'];
$dbname = $_ENV['MYSQL_DATABASE'];
$username = $_ENV['MYSQL_USER'];
$password = $_ENV['MYSQL_PASSWORD'];


try {
    $pdo = new PDO(
    "mysql:host=$host;port=" . $_ENV['MYSQL_PORT'] . ";dbname=$dbname",
    $username,
    $password
);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Fetch all published blogs ordered by creation date (newest first)
try {
    $stmt = $pdo->prepare("SELECT * FROM blogs WHERE status = 'published' ORDER BY created_at DESC");
    $stmt->execute();
    $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Error fetching blogs: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - Agri-Grow</title>
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
    <!-- Write Your Own Blog Button (always visible; write_blog.php will redirect to login if needed) -->
    <div style="text-align: center; margin-bottom: 15px;">
    <button
        type="button"
        onclick="window.location.href='./write_blog.php'"
        style="
            background-color: #65a30d; /* lime-600 */
            color: white;
            font-weight: bold;
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            box-shadow: 0px 4px 6px rgba(0,0,0,0.2);
            display: inline-flex;
            align-items: center;
            font-size: 16px;
            transition: all 0.3s ease;
        "
        onmouseover="this.style.backgroundColor='#4d7c0f'"
        onmouseout="this.style.backgroundColor='#65a30d'"
    >
        <i class="fas fa-pen-fancy" style="margin-right: 8px;"></i>
        Write Your Own Blog
    </button>
</div>


    <!-- Blog Posts -->
    <?php if (empty($blogs)): ?>
        <div class="text-center py-12">
            <div class="text-gray-400 text-lg mb-4">
                <i class="fas fa-newspaper text-6xl mb-4"></i>
                <h2 class="text-2xl font-bold mb-2">No blogs yet</h2>
                <p>Be the first to share your farming knowledge!</p>
            </div>
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                <a href="./write_blog.php" class="inline-flex items-center bg-lime-600 hover:bg-lime-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                    <i class="fas fa-pen-fancy mr-2"></i>
                    Write the First Blog
                </a>
            <?php else: ?>
                <a href="./login.php" class="inline-flex items-center bg-lime-600 hover:bg-lime-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Login to Write Blog
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <?php foreach ($blogs as $blog): ?>
            <article class="bg-gray-900 rounded-2xl p-8 mb-16">
                
                
                <h1 class="text-3xl font-bold mb-2"><?php echo htmlspecialchars($blog['title']); ?></h1>
                <div class="flex items-center text-sm text-gray-400 mb-4">
                    <?php if (!empty($blog['tags'])): ?>
                        
                    <?php endif; ?>
                    <span><?php echo date('F j, Y', strtotime($blog['created_at'])); ?></span>
                </div>
                <img src="<?php echo htmlspecialchars($blog['cover_image_url']); ?>" 
                     alt="<?php echo htmlspecialchars($blog['title']); ?>" 
                     class="w-full h-96 object-cover rounded-xl mb-8">
                
                <div class="space-y-6 text-gray-300">
                    <?php 
                    // Show only first 300 characters of content with "Read More" link
                    $content = $blog['content'];
                    $excerpt = substr($content, 0, 300);
                    if (strlen($content) > 300) {
                        $excerpt .= '...';
                    }
                    echo nl2br(htmlspecialchars($excerpt));
                    ?>
                </div>
                
                <div class="mt-6">
                    <a href="./view_blog.php?id=<?php echo $blog['id']; ?>" 
                       class="inline-flex items-center bg-lime-600 hover:bg-lime-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                        <i class="fas fa-arrow-right mr-2"></i>
                        Read Full Article
                    </a>
                </div>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</main>

<footer class="bg-gray-900 mt-5 w-full">
    <div class="flex justify-center items-center">
        <p>© 2021 AgriGrow. All rights reserved</p>
    </div>
</footer>

</body>
</html>

