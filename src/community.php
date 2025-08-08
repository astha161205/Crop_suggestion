<?php
session_start(); // Start the session
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agri-Grow</title>
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
      <a href="#blogs" class="hover:text-white">Blog</a>
      
      <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
        <a href="./profile.php" class="hover:text-white">Profile</a>
      <?php else: ?>
        <a href="./login.php" class="hover:text-white">Login</a>
      <?php endif; ?>
    </div>
  </header>

    <!-- Community Section -->
    <section class="p-10 flex flex-col items-center text-center gap-5">
        <h1 class="text-5xl font-bold text-lime-400">Our Community</h1>
        <p class="max-w-4xl text-lg text-gray-300">
            Join our growing community of farmers, agri-tech enthusiasts, and sustainability advocates! Together we learn, grow, and thrive.
        </p>

        

        <!-- Highlights -->
        <div class="mt-10 max-w-4xl text-left">
            <h2 class="text-2xl font-bold mb-4 text-white">💬 Community Highlights</h2>
            <ul class="list-disc list-inside text-gray-300 space-y-2">
                <li>🌾 Monthly webinars on sustainable agriculture.</li>
                <li>🧑‍🌾 Farmer success stories from across the country.</li>
                <li>🤝 Networking and mentorship opportunities.</li>
                <li>📢 Stay updated on AgriGrow events and initiatives.</li>
            </ul>
        </div>

        <!-- Connect with Community -->
        <div class="mt-10 max-w-3xl text-left">
            <h2 class="text-2xl font-bold mb-3 text-white">🔗 Connect with the AgriGrow Community</h2>
            <ul class="text-gray-300 space-y-4">
                <li class="flex items-center gap-3">
                    <img src="../photos/home/facebook.svg" alt="Facebook" class="h-5 w-5">
                    <a href="https://facebook.com/agrigrow" class="text-lime-400 underline" target="_blank">Facebook Page </a>
                </li>
                <li class="flex items-center gap-3">
                    <img src="../photos/home/insta.svg" alt="Instagram" class="h-5 w-5">
                    <a href="https://instagram.com/agrigrow" class="text-lime-400 underline" target="_blank">@agri_grow</a>
                </li>
                <li class="flex items-center gap-3">
                    <img src="../photos/home/twitter.svg" alt="Twitter" class="h-5 w-5">
                    <a href="https://twitter.com/agrigrow" class="text-lime-400 underline" target="_blank">@agrigrow</a>
                </li>
                
            </ul>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 mt-10 py-4  ">
        <div class="text-center text-gray-400">
            © 2025 AgriGrow. All rights reserved.
        </div>
    </footer>

</body>
</html>
