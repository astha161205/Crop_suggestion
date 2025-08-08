<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $cover_image_url = trim($_POST['cover_image_url']);
    $content = trim($_POST['content']);
    $tags = trim($_POST['tags']);
    
    // Validation
    $errors = [];
    if (empty($title)) $errors[] = "Blog title is required";
    if (empty($cover_image_url)) $errors[] = "Cover image URL is required";
    if (empty($content)) $errors[] = "Blog content is required";
    
            if (empty($errors)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO blogs (title, cover_image_url, content, tags, author_id, author_name) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$title, $cover_image_url, $content, $tags, null, $_SESSION['user_name']]);
                
                $success_message = "Blog published successfully!";
                // Clear form data after successful submission
                $title = $cover_image_url = $content = $tags = '';
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
      <a href="./profile.php" class="hover:text-white">Profile</a>
    </div>
</header>

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

        <form method="POST" class="space-y-6">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-300 mb-2">Blog Title *</label>
                <input type="text" id="title" name="title" value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>" 
                       class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:border-lime-500 text-white" 
                       placeholder="Enter your blog title" required>
            </div>

            <div>
                <label for="cover_image_url" class="block text-sm font-medium text-gray-300 mb-2">Cover Image URL *</label>
                <input type="url" id="cover_image_url" name="cover_image_url" value="<?php echo isset($cover_image_url) ? htmlspecialchars($cover_image_url) : ''; ?>" 
                       class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:border-lime-500 text-white" 
                       placeholder="https://example.com/image.jpg" required>
                <p class="text-sm text-gray-500 mt-1">You can use any image URL or upload to a service like Imgur</p>
            </div>

            <div>
                <label for="content" class="block text-sm font-medium text-gray-300 mb-2">Content *</label>
                <textarea id="content" name="content" rows="15" 
                          class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:border-lime-500 text-white resize-vertical" 
                          placeholder="Write your blog content here..." required><?php echo isset($content) ? htmlspecialchars($content) : ''; ?></textarea>
                <p class="text-sm text-gray-500 mt-1">You can use basic formatting like line breaks and paragraphs</p>
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

        <div class="mt-8 p-6 bg-gray-800 rounded-lg">
            <h3 class="text-lg font-bold text-lime-300 mb-3">Writing Tips:</h3>
            <ul class="text-gray-300 space-y-2">
                <li>• Start with a compelling introduction to grab readers' attention</li>
                <li>• Use clear, simple language that farmers can understand</li>
                <li>• Include practical tips and real-world examples</li>
                <li>• Break up long paragraphs for better readability</li>
                <li>• Add relevant images to illustrate your points</li>
                <li>• End with actionable takeaways for your readers</li>
            </ul>
        </div>
    </div>
</main>

<footer class="bg-gray-900 mt-5 w-full">
    <div class="flex justify-center items-center">
        <p>© 2021 AgriGrow. All rights reserved</p>
    </div>
</footer>

</body>
</html>
