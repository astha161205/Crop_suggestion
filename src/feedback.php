<?php
session_start();
require __DIR__ . '/../vendor/autoload.php'; // adjust path if needed

// Only load .env if it exists (prevents fatal error in production)
$dotenvPath = __DIR__ . '/../.env';
if (file_exists($dotenvPath)) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>feedback - AgriGrow</title>
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
</head>
<body class="font-mono bg-gray-950 text-white ">

  <!-- Header -->
  <?php include 'header.php'; ?>

  
  <div class="max-w-5xl mx-auto flex flex-col md:flex-row gap-6 bg-gray-950">

      <div class="flex-1 bg-gray-800 p-5 rounded-2xl shadow-md overflow-auto">
        <h2 class="text-3xl font-bold mb-6 text-lime-400">feedback form</h2>
        <form action="./feedback2.php" method="POST" class="space-y-6">
          <div>
            <label for="name" class="block text-sm font-semibold text-gray-300 mb-1">Name</label>
            <input type="text" id="name" name="name" required
              class="w-full px-4 py-3 rounded-lg bg-gray-900 text-white border border-gray-700 focus:outline-none focus:border-lime-400" />
          </div>
          <div>
            <label for="email" class="block text-sm font-semibold text-gray-300 mb-1">Email</label>
            <input type="email" id="email" name="email" required
              class="w-full px-4 py-3 rounded-lg bg-gray-900 text-white border border-gray-700 focus:outline-none focus:border-lime-400" />
          </div>
          <div>
            <label for="message" class="block text-sm font-semibold text-gray-300 mb-1">Message</label>
            <textarea id="message" name="message" rows="5" required
              class="w-full px-4 py-3 rounded-lg bg-gray-900 text-white border border-gray-700 focus:outline-none focus:border-lime-400"></textarea>
          </div>
          <div class="flex justify-end">
            <button type="submit"
              class="bg-lime-500 hover:bg-lime-600 text-black font-semibold px-6 py-3 rounded-xl transition duration-300">
              Send
            </button>
          </div>
        </form>
      </div>

    </div>


</body>
</html>
