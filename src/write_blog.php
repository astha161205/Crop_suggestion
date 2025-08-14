<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();
// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}
// Database connection using env vars
$host = $_ENV['MYSQL_HOST'];
$port = $_ENV['MYSQL_PORT'];
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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $cover_image_url = trim($_POST['cover_image_url']);
    $content = trim($_POST['content']);
    $tags = trim($_POST['tags']);

    $errors = [];
    if (empty($title)) $errors[] = "Blog title is required";
    if (empty($content)) $errors[] = "Blog content is required";

    // Image: allow either URL or file upload
    $hasFile = isset($_FILES['cover_image_file']) && isset($_FILES['cover_image_file']['tmp_name']) && $_FILES['cover_image_file']['error'] === UPLOAD_ERR_OK;
    if (!$hasFile && empty($cover_image_url)) {
        $errors[] = "Please provide a cover image URL or upload an image.";
    }

    $cover_image_to_save = $cover_image_url; // default to provided URL

    // If a file is uploaded, validate and store it
    if ($hasFile) {
        $allowedExt = ['jpg','jpeg','png','webp'];
        $originalName = $_FILES['cover_image_file']['name'];
        $tmpPath = $_FILES['cover_image_file']['tmp_name'];
        $sizeBytes = (int)$_FILES['cover_image_file']['size'];
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExt, true)) {
            $errors[] = "Invalid image type. Allowed: JPG, JPEG, PNG, WEBP.";
        }

        // Basic image check
        $imgInfo = @getimagesize($tmpPath);
        if ($imgInfo === false) {
            $errors[] = "Uploaded file is not a valid image.";
        }

        // Size limit ~5MB
        if ($sizeBytes > 5 * 1024 * 1024) {
            $errors[] = "Image too large. Max size is 5MB.";
        }

        if (empty($errors)) {
            $uploadsDir = __DIR__ . '/uploads/blogs';
            if (!is_dir($uploadsDir)) {
                @mkdir($uploadsDir, 0775, true);
            }
            $safeBase = 'blog_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $destPath = $uploadsDir . '/' . $safeBase;

            if (@move_uploaded_file($tmpPath, $destPath)) {
                // Public URL relative to src/
                $cover_image_to_save = './uploads/blogs/' . $safeBase;
            } else {
                $errors[] = "Failed to save the uploaded image. Please try again.";
            }
        }
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO blogs (title, cover_image_url, content, tags, author_id, author_name) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $cover_image_to_save, $content, $tags, null, $_SESSION['user_name']]);

            $success_message = "Blog published successfully!";
            // Clear form data after successful submission
            $title = $cover_image_url = $content = $tags = '';
            // Optionally redirect to blog list
            // header('Location: blog.php'); exit;
        } catch(PDOException $e) {
            $errors[] = "Error publishing blog: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Write Your Own Blog - Agri-Grow</title>
    <link rel="icon" href="../photos/home/favicon2.svg" type="image/svg+xml">
    <link href="./output.css" rel="stylesheet">
    <link rel="stylesheet" href="./homecss.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="font-mono bg-gray-950 text-white relative">

<?php include 'header.php'; ?>

<main class="container mx-auto px-4 py-12 max-w-4xl">
    <div class="bg-gray-900 rounded-2xl p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold mb-4">Write Your Own Blog</h1>
            <p class="text-gray-400 text-lg">Share your story, expertise, or passion. Your words can help others grow.</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-900/20 border border-red-500 text-red-300 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc list-inside">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (isset($success_message)): ?>
            <div class="bg-green-900/20 border border-green-500 text-green-300 px-4 py-3 rounded-lg mb-6">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6" enctype="multipart/form-data">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-300 mb-2">Blog Title *</label>
                <input type="text" id="title" name="title" value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>" 
                       class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:border-lime-500 text-white" 
                       placeholder="Enter your blog title" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Cover Image</label>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label for="cover_image_url" class="block text-xs text-gray-400 mb-1">Use an Image URL</label>
                        <input type="url" id="cover_image_url" name="cover_image_url" value="<?php echo isset($cover_image_url) ? htmlspecialchars($cover_image_url) : ''; ?>" 
                               class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:border-lime-500 text-white" 
                               placeholder="https://example.com/image.jpg">
                    </div>
                    <div>
                        <label for="cover_image_file" class="block text-xs text-gray-400 mb-1">Or upload an image (JPG, PNG, WEBP, max 5MB)</label>
                        <input type="file" id="cover_image_file" name="cover_image_file" accept="image/jpeg,image/png,image/webp" 
                               class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:border-lime-500 text-white">
                    </div>
                </div>
                <p class="text-sm text-gray-500 mt-1">Provide either a URL or upload a file. If both are provided, the uploaded file will be used.</p>
            </div>

            <div>
                <label for="content" class="block text-sm font-medium text-gray-300 mb-2">Content *</label>
                <textarea id="content" name="content" rows="15" 
                          class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:border-lime-500 text-white resize-vertical" 
                          placeholder="Write your blog content here..." required><?php echo isset($content) ? htmlspecialchars($content) : ''; ?></textarea>
                
            </div>

            <div>
                <label for="tags" class="block text-sm font-medium text-gray-300 mb-2">Tags</label>
                <input type="text" id="tags" name="tags" value="<?php echo isset($tags) ? htmlspecialchars($tags) : ''; ?>" 
                       class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:border-lime-500 text-white" 
                       placeholder="e.g., Farming, Technology, Tips (separate with commas)">
                <p class="text-sm text-gray-500 mt-1">Add relevant tags to help others find your blog</p>
            </div>

            <div class="flex gap-4 pt-6">
                <button type="submit" class="flex-1 bg-lime-600 hover:bg-lime-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                    <i class="fas fa-paper-plane mr-2"></i>Publish Blog
                </button>
                <a href="./blog.php" class="flex-1 bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300 text-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Blogs
                </a>
            </div>
        </form>

        
    </div>
</main>

<footer class="bg-gray-900 mt-5 w-full p-2">
    <div class="flex ">
        <p>© 2025 AgriGrow. All rights reserved</p>
    </div>
</footer>

</body>
</html>
