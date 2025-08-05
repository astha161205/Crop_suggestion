<?php
session_start(); // Start the session
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Farm Tech - AgriGrow</title>
  <link rel="icon" href="../photos/home/favicon2.svg" type="image/svg+xml">
  <link href="./output.css" rel="stylesheet" />
  <link rel="stylesheet" href="./homecss.css" />

  <style>
    .slide-info {
      transition: transform 0.5s ease, opacity 0.5s ease;
      transform: translateX(100%);
      opacity: 0;
      position: absolute;
      top: 14rem;
      right: 2rem;
      width: 90%;
      max-width: 420px;
      z-index: 10;
    }

    .slide-info.show {
      transform: translateX(0%);
      opacity: 1;
    }
  </style>

  <script>
    function toggleTechInfo() {
      const info = document.getElementById("techInfo");
      info.classList.toggle("show");
    }
  </script>
</head>


<body class="font-mono bg-gray-950 text-white relative">



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
    <!-- Content Section -->
    <section class="p-10 flex flex-col items-center text-center gap-5 relative">
      <h1 class="text-5xl font-bold text-lime-400">Farm Tech</h1>
      <p class="max-w-4xl text-lg text-gray-300">
        Discover how technology is transforming farming for the better. From smart sensors and automated irrigation to AI-powered analytics, Farm Tech is at the heart of modern sustainable agriculture.
      </p>

      <div class="flex flex-col lg:flex-row justify-around items-center gap-10">
        <img src="../photos/home/farm_tech.svg" alt="Farm Tech"
          class="h-48 w-auto mt-5 rounded-xl transition-transform duration-500 hover:scale-105">

        <div class="mt-10 max-w-5xl text-left">
          <h2 class="text-2xl font-bold mb-3">🔍 What We Offer:</h2>
          <ul class="list-disc list-inside text-gray-300 space-y-2">
            <li>Precision agriculture tools and techniques</li>
            <li>Data-driven crop monitoring systems</li>
            <li>Smart irrigation solutions</li>
            <li>IoT integration for real-time data</li>
          </ul>
        </div>
      </div>

      <!-- Toggle Button -->
      <button onclick="toggleTechInfo()" class="mt-6 bg-lime-500 hover:bg-lime-600 text-black font-semibold px-5 py-2 rounded-xl transition duration-300">
        Show More Tech Benefits
      </button>

      <!-- Slide-out Panel -->
      <div id="techInfo" class="slide-info bg-gray-800 p-6 rounded-2xl shadow-xl text-left">
        <h2 class="text-xl font-bold text-white mb-3">🌐 Advanced Farm Tech Perks</h2>
        <ul class="text-gray-300 space-y-3">
          <li>📡 Satellite-driven yield mapping</li>
          <li>🌦️ Climate-smart crop prediction AI</li>
          <li>🔋 Solar-powered IoT sensor kits</li>
          <li>📲 Remote control for irrigation/fertilization</li>
        </ul>
      </div>

      <!-- Push footer down with dummy content -->
      <div class="mt-20 max-w-4xl text-left">
        <h2 class="text-3xl font-semibold text-lime-400 mb-4">Why It Matters</h2>
        <p class="text-gray-300 mb-6">
          Technological innovation in agriculture ensures more food production with fewer resources. It helps reduce waste, monitor soil health, and make timely decisions that can boost yield and protect the environment.
        </p>
        <p class="text-gray-300">
          Farmers can now receive real-time alerts, automate crop cycles, and make smarter, data-driven decisions. With Farm Tech, the future of agriculture is not just smart—it's sustainable and scalable.
        </p>
      </div>

    </section>
  </div>

  <!-- Footer -->
  <footer class="bg-gray-900 py-4 w-full">
    <div class="text-center text-gray-400">
      © 2025 AgriGrow. All rights reserved.
    </div>
  </footer>

</body>
</html>
