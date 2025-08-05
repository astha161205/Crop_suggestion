<?php
session_start(); // Start the session
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sustainable Farming - AgriGrow</title>
    <link rel="icon" href="../photos/home/favicon2.svg" type="image/svg+xml">
    <link href="./output.css" rel="stylesheet">
    <link rel="stylesheet" href="./homecss.css">

    <!-- AOS (Animate On Scroll) Library -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
</head>
<body class="font-mono bg-gray-950 text-white">

    <!-- Header -->
    <header class="flex justify-between items-center bg-gray-950 h-15 sticky z-20 border-b-2 border-b-gray-900 top-0 pl-3 pr-3">
    <div class="flex gap-2 items-center">
        <a href="./homePage.php" class="flex items-center gap-2">
            <img src="../photos/home/logo.png" alt="logo" class="h-10 w-10 rounded-4xl">
            <h3 class="">AgriGrow</h3>
        </a>
    </div>

    <div class="text-gray-400 flex gap-6 pl-5 pr-4 pt-1 pb-1 ml-auto">
        <a href="./homePage.php" class="hover:text-white">Home</a>
        <a href="./SUNSIDIES.php" class="hover:text-white">Subsidies</a>
        <a href="./blog.php" class="hover:text-white">Blog</a>
        <a href="./homePage.php#About" class="hover:text-white">About us</a>
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
            <a href="./profile.php" class="hover:text-white">Profile</a>
        <?php else: ?>
            <a href="./login.php" class="hover:text-white">Login</a>
        <?php endif; ?>
    </div>
</header>
    
      

    <!-- Sustainable Farming Section -->
    <section class="p-10 flex flex-col items-center text-center gap-5">
        <h1 class="text-5xl font-bold text-lime-400" data-aos="fade-up">Sustainable Farming</h1>
        <p class="max-w-4xl text-lg text-gray-300" data-aos="fade-up" data-aos-delay="100">
            At AgriGrow, we promote eco-friendly farming practices that preserve the environment, ensure long-term productivity, and uplift farming communities.
        </p>

        <div class="flex flex-wrap justify-center gap-10 mt-10" data-aos="fade-up" data-aos-delay="200">
            <img src="../photos/home/sustainable.svg" alt="Sustainable Farming" class="h-60 w-60 mt-5 rounded-xl transition-transform duration-500 hover:scale-105">

            <!-- Our Approach -->
            <div class="mt-5 max-w-2xl text-left">
                <h2 class="text-2xl font-bold mb-4 text-white">🌱 Our Sustainable Approach</h2>
                <ul class="list-disc list-inside text-gray-300 space-y-2">
                    <li>Use of organic fertilizers and bio-pesticides</li>
                    <li>Water conservation through drip and sprinkler systems</li>
                    <li>Encouraging crop rotation and polyculture</li>
                    <li>Minimal soil disturbance and zero tillage techniques</li>
                    <li>Solar-powered irrigation and energy-efficient practices</li>
                </ul>
            </div>
        </div>

        <!-- Benefits Section -->
        <div class="mt-10 max-w-4xl text-left" data-aos="fade-up" data-aos-delay="300">
            <h2 class="text-2xl font-bold mb-4 text-white">🌍 Why Sustainable Farming?</h2>
            <p class="text-gray-300">
                Sustainable agriculture helps reduce greenhouse emissions, ensures food security, and protects biodiversity. It’s our step towards a greener and more resilient future.
            </p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 mt-10 py-4 shadow-inner">
        <div class="text-center text-gray-400">
            © 2025 AgriGrow. All rights reserved.
        </div>
    </footer>

    <!-- Initialize AOS -->
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });
    </script>
</body>
</html>
