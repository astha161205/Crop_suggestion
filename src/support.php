<?php
session_start(); // Start the session
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Support - AgriGrow</title>
  <link rel="icon" href="../photos/home/favicon2.svg" type="image/svg+xml">
  <link href="./output.css" rel="stylesheet" />
  <link rel="stylesheet" href="./homecss.css" />
  <style>
    .slide-panel {
      transition: transform 0.5s ease, opacity 0.5s ease;
      transform: translateX(100%);
      opacity: 0;
      position: absolute;
      right: 2rem;
      top: 12rem;
      width: 90%;
      max-width: 400px;
      z-index: 10;
    }

    .slide-panel.show {
      transform: translateX(0%);
      opacity: 1;
    }
  </style>
  <script>
    function toggleContact() {
      const section = document.getElementById("contactSection");
      section.classList.toggle("show");
    }
  </script>
</head>
<body class="font-mono bg-gray-950 text-white relative overflow-x-hidden">

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

  <!-- Support Content -->
  <section class="p-10 pb-24 flex flex-col items-center text-center gap-5 relative">
    <h1 class="text-5xl font-bold text-lime-400">Support</h1>
    <p class="max-w-4xl text-lg text-gray-300">
      Need help or have questions? Our support team is here for you 24/7. Reach out via email or connect with us on social platforms.
    </p>

    <img src="../photos/home/support.svg" alt="Support"
      class="h-48 w-auto mt-5 rounded-xl border border-gray-800 shadow-md transition-transform duration-500 hover:scale-105">

    <button onclick="toggleContact()" class="mt-6 bg-lime-500 hover:bg-lime-600 text-black font-semibold px-5 py-2 rounded-xl transition duration-300">
      Toggle Contact Info
    </button>
    <!-- Help & Support Form -->
<div class="w-full max-w-2xl bg-gray-800 p-6 rounded-2xl shadow-md mt-10">
  <h2 class="text-2xl font-bold mb-4 text-lime-400">📝 Submit a Support Request</h2>
  <form action="./support2.php" method="GET" class="space-y-4">
    <div>
      <label for="name" class="block text-sm font-medium text-gray-300">Name</label>
      <input type="text" id="name" name="name" required class="mt-1 w-full px-4 py-2 rounded-lg bg-gray-900 text-white border border-gray-700 focus:outline-none focus:border-lime-400" />
    </div>
    <div>
      <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
      <input type="email" id="email" name="email" required class="mt-1 w-full px-4 py-2 rounded-lg bg-gray-900 text-white border border-gray-700 focus:outline-none focus:border-lime-400" />
    </div>
    <div>
      <label for="message" class="block text-sm font-medium text-gray-300">Message</label>
      <textarea id="message" name="message" rows="4" required class="mt-1 w-full px-4 py-2 rounded-lg bg-gray-900 text-white border border-gray-700 focus:outline-none focus:border-lime-400"></textarea>
    </div>
    <div class="flex justify-end">
      <button type="submit" class="bg-lime-500 hover:bg-lime-600 text-black font-semibold px-6 py-2 rounded-xl transition duration-300">
        Send
      </button>
    </div>
  </form>
</div>


    
<div class="mt-16 text-left max-w-4xl">
    <h2 class="text-3xl font-semibold text-lime-400 mb-4">Frequently Asked Questions</h2>
    <ul class="space-y-6 text-gray-300">
      <li>
        <strong>🌾 How do I get crop suggestions?</strong>
        <p class="ml-4 mt-1">Simply enter your soil type, weather details, and season. Our AI will suggest the most profitable crops for your region.</p>
      </li>
      <li>
        <strong>📊 Can I access past data?</strong>
        <p class="ml-4 mt-1">Yes, your dashboard stores historical recommendations and feedback to help improve future predictions.</p>
      </li> 
    
  

    <!-- Slide-out Contact Section -->
    <div id="contactSection" class="slide-panel bg-gray-800 p-6 rounded-2xl shadow-lg text-left transition-transform duration-500">
      <h2 class="text-2xl font-bold mb-3 text-white">📬 Contact Us</h2>
      <ul class="text-gray-300 space-y-4">
        <li><strong>Email:</strong> <a href="mailto:support@agrigrow.com" class="text-lime-400 underline">muwahidmir2@gmail.com</a></li>
        <li><strong>Instagram:</strong> <a href="https://instagram.com/agrigrow" class="text-lime-400 underline" target="_blank">muwahidmir</a></li>
        <li><strong>Snapchat:</strong> <span class="text-lime-400">muwahidmir</span></li>
        <li><strong>LinkedIn:</strong> <a href="https://linkedin.com/company/agrigrow" class="text-lime-400 underline" target="_blank">Muwahid Mir</a></li>
      </ul>
    </div>
  </section>

  <!-- Fixed Footer -->
  <footer class="bg-gray-900 py-2 fixed bottom-0 w-full z-10">
    <div class="text-center text-gray-400">
      © 2025 AgriGrow. All rights reserved.
    </div>
  </footer>

</body>
</html>
