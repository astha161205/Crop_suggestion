<?php
session_start();
require __DIR__ . '/../vendor/autoload.php'; // adjust path if needed

// Only load .env if it exists (prevents fatal error in production)
$dotenvPath = __DIR__ . '/../.env';
if (file_exists($dotenvPath)) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
}

$host = getenv('MYSQL_HOST') ?: 'localhost';
$port = getenv('MYSQL_PORT') ?: '3306';
$dbname = getenv('MYSQL_DATABASE') ?: 'crop';
$username = getenv('MYSQL_USER') ?: 'root';
$password = getenv('MYSQL_PASSWORD') ?: '';
try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8",
        $username,
        $password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
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
    <link rel="icon" href="./home/favicon2.svg" type="image/svg+xml">
    <link href="./output.css" rel="stylesheet">
    <link rel="stylesheet" href="./homecss.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="font-mono bg-gray-950 text-white relative">

<?php include 'header.php'; ?>

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
        

        <!-- Blog title -->
        <h1 class="text-3xl font-bold mb-2"><?php echo htmlspecialchars($blog['title']); ?></h1>
        <div class="flex items-center text-sm text-gray-400 mb-4">
            
            <span><?php echo $formatted_date; ?></span>
        </div>
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

        
    </article>
</main>

<footer class="bg-gray-900 mt-5 w-full">
    <div class="flex justify-center items-center">
        <p>© 2021 AgriGrow. All rights reserved</p>
    </div>
</footer>

</body>
</html>

