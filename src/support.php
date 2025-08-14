<?php
session_start();
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
</head>
<body class="font-mono bg-gray-950 text-white ">

  <!-- Header -->
  <?php include 'header.php'; ?>
  <!-- Main Section -->
  <section class="w-full px-6 py-12 bg-gray-950">
  <div class="max-w-6xl mx-auto flex flex-col md:flex-row gap-6">

      <!-- Left Box -->
      <div class="flex-1 bg-gray-800 p-8 rounded-2xl shadow-md overflow-auto">
        <h2 class="text-3xl font-extrabold mb-4 text-lime-400">📝 How can we help?</h2>
        <p class="mb-6 text-gray-300 leading-relaxed">
          Need help or have questions? Our support team is available 24/7. Reach out via email or connect with us on social media.
        </p>

        <h3 class="text-xl font-bold text-lime-400 mb-3">📞 Contact Info</h3>
        <ul class="space-y-2 text-gray-300 mb-8">
          <li><strong>Email:</strong> <a href="mailto:singhalastha26@gmail.com" class="text-lime-400 underline">Astha Singhal</a></li>
          <li><strong>Instagram:</strong> <a href="https://instagram.com/astha161205" class="text-lime-400 underline">Astha Singhal</a></li>
          <li><strong>LinkedIn:</strong> <a href="https://www.linkedin.com/in/asthasinghal24/" class="text-lime-400 underline">Astha Singhal</a></li>
        </ul>

        <h3 class="text-xl font-bold text-lime-400 mb-3">❓ FAQs</h3>
        <ul class="space-y-4 text-gray-300 text-sm">
          <li>
            <strong>🌾 How do I get crop suggestions?</strong>
            <p class="ml-4 mt-1">Enter your soil type, weather, and season. Our AI will suggest the most profitable crops for your area.</p>
          </li>
          <li>
            <strong>📊 Can I access past data?</strong>
            <p class="ml-4 mt-1">Yes! Your dashboard stores historical recommendations and feedback.</p>
          </li>
        </ul>
      </div>

      <!-- Right Box -->
      <div class="flex-1 bg-gray-800 p-10 rounded-2xl shadow-md overflow-auto">
        <h2 class="text-3xl font-extrabold mb-6 text-lime-400">Contact Support</h2>
        <form action="./support2.php" method="POST" class="space-y-6">
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
  </section>

</body>
</html>
