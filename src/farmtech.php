<?php
session_start();
// Load .env (optional) so GEMINI_API_KEY can be stored in a .env file at project root
require __DIR__ . '/../vendor/autoload.php'; // adjust path if needed

// Only load .env if it exists (prevents fatal error in production)
$dotenvPath = __DIR__ . '/../.env';
if (file_exists($dotenvPath)) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
}
// Dummy testimonials and technologies arrays
$testimonials = [
  [
    "name" => "John D.",
    "avatar" => "JD",
    "image" => "https://placehold.co/100x100",
    "hint" => "farmer portrait",
    "title" => "A Game Changer for Water Savings",
    "quote" => "The precision irrigation system recommended by AgriAssist cut our water usage by 30%. My crops have never been healthier, and my utility bills have never been lower. It's truly a game-changer. ",
  ],
  [
    "name" => "Maria S.",
    "avatar" => "MS",
    "image" => "https://placehold.co/100x100",
    "hint" => "farmer smiling",
    "title" => "Efficiency Like Never Before",
    "quote" => "I was skeptical about autonomous tractors, but the efficiency gains are undeniable. I can now manage my 1000-acre farm with a smaller team, saving time and money. ",
  ],
  [
    "name" => "Chen W.",
    "avatar" => "CW",
    "image" => "https://placehold.co/100x100",
    "hint" => "woman farmer",
    "title" => "Data-driven Decisions",
    "quote" => "Using drones for crop monitoring has revolutionized how I approach pest management. I can spot issues weeks earlier than before and apply treatments with surgical precision. ",
  ],
];

$technologies = [
  [
    "name" => "Precision Irrigation Systems",
    "description" => "Optimize water usage with sensors and automated controls, delivering water exactly when it’s needed.",
    "category" => "Water Management",
    "image" => "./home/irirgation.png",
    "icon" => "💧",
    "link" => "https://eos.com/blog/precision-irrigation/",
  ],
  [
    "name" => "Autonomous Tractors",
    "description" => "Increase efficiency and reduce labor costs with self-driving tractors for tasks like planting, tilling, and harvesting.",
    "category" => "Automation",
    "image" => "./home/autonomus_tractors.jpg",
    "icon" => "🚜",
    "link" => "https://www.deere.com/en/autonomous/",
  ],
  [
    "name" => "Renewable Energy Solutions",
    "description" => "Use solar and wind power to reduce your farm's carbon footprint, energy costs and environmental impact.",
    "category" => "Sustainability",
    "image" => "./home/renewable_resoure.png",
    "icon" => "🌱",
    "link" => "https://www.hitachienergy.com/in/en/markets/renewable-energy",
  ],
  [
    "name" => "Agricultural Drone ",
    "description" => "Utilize drones for real-time crop health monitoring and pest detection.",
    "category" => "Data & Monitoring",
    "image" => "./home/drones.webp",
    "icon" => "🚁",
    "link" => "https://ag.dji.com/",
  ],
  [
    "name" => "Vertical Farming Systems",
    "description" => "Grow crops in stacked layers, often indoors, to maximize space and control growing conditions year-round.",
    "category" => "Indoor Farming",
    "image" => "./home/Vertical Farming Systems.png",
    "icon" => "🏙️",
    "link" => "https://www.cropin.com/vertical-farming/#:~:text=Vertical%20farming%20refers%20to%20the,warehouses%2C%20and%20abandoned%20mine%20shafts.",
  ],
  [
    "name" => "Soil Health Sensor",
    "description" => "Real-time monitoring of soil moisture, nutrient levels, and pH to make informed decisions about fertilization ",
    "category" => "Water Management",
    "image" => "./home/soil health.jpg",
    "icon" => "🐞",
    "link" => "https://www.renkeer.com/5-types-soil-sensors/",
  ]
];
$categories = [
  "All",
  "Water Management",
  "Automation",
  "Data & Monitoring",
  "Indoor Farming",
  "Sustainability"
];

$selected_category = isset($_GET['category']) ? $_GET['category'] : "All";
$search_query = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : "";

// Filter technologies by category and search
$filtered_technologies = array_filter($technologies, function($tech) use ($selected_category, $search_query) {
    $matches_category = ($selected_category === "All") || ($tech['category'] === $selected_category);
    $matches_search = empty($search_query) || (strpos(strtolower($tech['name']), $search_query) !== false) || (strpos(strtolower($tech['description']), $search_query) !== false);
    return $matches_category && $matches_search;
});
// Helper: local fallback recommendation (no external API required)
function generateLocalRecommendation(string $farmSize, string $crops, string $challenges, array $technologies): string {
    $advice = [];
    $lowerChallenges = strtolower($challenges);

    if (preg_match('/water|irrig|drought|moisture/', $lowerChallenges)) {
        $advice[] = 'Consider precision irrigation and soil-moisture sensors to optimize water use and reduce stress during dry periods.';
    }
    if (preg_match('/pest|disease|infest|aphid|worm|mite/', $lowerChallenges)) {
        $advice[] = 'Adopt integrated pest management (IPM): monitoring, biological controls, and targeted spraying (drones can help).';
    }
    if (preg_match('/labor|efficien|cost|harvest|planting/', $lowerChallenges)) {
        $advice[] = 'Explore automation like autonomous tractors for planting/harvesting to cut labor costs and improve efficiency.';
    }
    if (preg_match('/monitor|yield|health|mapping|data|variab/', $lowerChallenges)) {
        $advice[] = 'Use drones and farm sensors for crop-health mapping and data-driven decisions to boost yield and input efficiency.';
    }
    if (!$advice) {
        $advice[] = 'Start with a soil health test, then plan irrigation, pest prevention, and crop rotation based on results.';
    }

    // Pick up to 3 relevant technologies based on keywords
    $picks = [];
    foreach ($technologies as $tech) {
        if (count($picks) >= 3) break;
        $hay = strtolower($tech['name'].' '.$tech['description'].' '.$tech['category']);
        if (
            (strpos($hay, 'irrig') !== false && preg_match('/water|irrig|drought|moisture/', $lowerChallenges)) ||
            (strpos($hay, 'drone') !== false && preg_match('/pest|disease|monitor|mapping/', $lowerChallenges)) ||
            (strpos($hay, 'tractor') !== false && preg_match('/labor|efficien|cost/', $lowerChallenges)) ||
            (strpos($hay, 'sensor') !== false && preg_match('/monitor|soil|moisture|data/', $lowerChallenges))
        ) {
            $picks[] = $tech;
        }
    }
    // If no matches, just take first 2
    if (!$picks) {
        $picks = array_slice($technologies, 0, 2);
    }

    $html = '<div class="space-y-3">';
    $html .= '<p><strong>Farm size:</strong> '.htmlspecialchars($farmSize).'</p>';
    $html .= '<p><strong>Crops:</strong> '.htmlspecialchars($crops).'</p>';
    $html .= '<p><strong>Challenges:</strong> '.htmlspecialchars($challenges).'</p>';
    $html .= '<h4><strong>Actionable tips:</strong></h4><ul>';
    foreach ($advice as $tip) {
        $html .= '<li>• '.htmlspecialchars($tip).'</li>';
    }
    $html .= '</ul>';
    $html .= '<h4 class="mt-3"><strong>Suggested technologies:</strong></h4><ul>';
    foreach ($picks as $p) {
        $html .= '<li>• <a class="text-lime-400" target="_blank" rel="noopener" href="'.htmlspecialchars($p['link']).'">'.htmlspecialchars($p['name']).'</a> — '.htmlspecialchars($p['description']).'</li>';
    }
    $html .= '</ul>';
    $html .= '<p class="text-xs text-gray-400 mt-2">(Offline recommendation generated locally.)</p>';
    $html .= '</div>';
    return $html;
}

// Modal logic
$show_modal = false;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $farmSize = $_POST['farmSize'] ?? '';
    $crops = $_POST['crops'] ?? '';
    $challenges = $_POST['challenges'] ?? '';

    $recommendation_html = '';
    $usedFallback = false;
    $showAiDebug = getenv('SHOW_AI_DEBUG') === '1';

    // Prefer environment variable for API key (match pest.php behavior)
    $api_key = $_ENV['GEMINI_API_KEY'] ?? (getenv('GEMINI_API_KEY') ?: '');

    // Decide whether to attempt API call
    $canCallApi = $api_key !== '' && extension_loaded('curl');

    if ($canCallApi) {
        $user_prompt = "You are an agriculture expert. A farmer has a farm size of $farmSize, grows $crops, and is facing the following challenges: $challenges. Give concise, practical advice about best practices, pest prevention, and irrigation scheduling in 5-7 bullet points.";

        // Use same stable endpoint as pest.php
        $url = "https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent?key=".$api_key;
        $payload = [
            'contents' => [ [ 'parts' => [ ['text' => $user_prompt] ] ] ]
        ];

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_TIMEOUT => 15,
        ]);
        $response = curl_exec($ch);
        $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr = curl_error($ch);
        curl_close($ch);

        if ($response !== false && $httpCode >= 200 && $httpCode < 300) {
            $result = json_decode($response, true);
            if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                $recommendation_html = nl2br(htmlspecialchars($result['candidates'][0]['content']['parts'][0]['text']));
            } elseif (isset($result['error']['message'])) {
                $usedFallback = true;
                $recommendation_html = generateLocalRecommendation($farmSize, $crops, $challenges, $technologies);
                if ($showAiDebug) {
                    $recommendation_html .= '<p class="text-xs text-red-300 mt-2">API error: '.htmlspecialchars($result['error']['message']).'</p>';
                }
            } else {
                $usedFallback = true;
                $recommendation_html = generateLocalRecommendation($farmSize, $crops, $challenges, $technologies);
            }
        } else {
            $usedFallback = true;
            $errMsg = $curlErr ?: ('HTTP '.$httpCode);
            $recommendation_html = generateLocalRecommendation($farmSize, $crops, $challenges, $technologies);
            if ($showAiDebug) {
                $recommendation_html .= '<p class="text-xs text-red-300 mt-2">API request failed: '.htmlspecialchars($errMsg).'</p>';
            }
        }
    } else {
        $usedFallback = true;
        $recommendation_html = generateLocalRecommendation($farmSize, $crops, $challenges, $technologies);
        if ($showAiDebug) {
            if (!extension_loaded('curl')) {
                $recommendation_html .= '<p class="text-xs text-yellow-300 mt-2">Note: PHP cURL extension is not enabled; using offline recommendations.</p>';
            } elseif ($api_key === '') {
                $recommendation_html .= '<p class="text-xs text-yellow-300 mt-2">Note: No GEMINI_API_KEY configured; using offline recommendations.</p>';
            }
        }
    }

    $show_modal = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>AgriGrow - Farm Technologies</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 min-h-screen flex flex-col">

  <!-- Header -->
  <?php include 'header.php'; ?>

  <main class="flex-1">
    <!-- Hero Section -->
    <section class="p-8">
      <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <!-- Left: Heading and Description -->
        <div>
          <span class="text-white font-bold text-lg">AI-Powered Assistance</span>
          <h1 class="text-5xl font-extrabold mt-4 mb-4 leading-tight text-white">Smarter Farming Starts Here</h1>
          <p class="text-gray-300 text-lg mb-2">
            Get personalized technology recommendations for your farm. Simply describe your operation and challenges, and our AI will suggest solutions to boost your productivity and sustainability.
          </p>
        </div>
        <!-- Right: Form Card -->
        <div>
          <div class="bg-gray-800 p-8 rounded-xl shadow-lg ">
            <h2 class="font-bold text-2xl mb-2 text-white">Find Your Farming Solution</h2>
            <p class="text-sm text-gray-400 mb-6">Fill out the form below to get started.</p>
            <form method="POST" class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-gray-200 font-semibold mb-1" for="farmSize">Farm Size (in acres)</label>
                  <input type="text" name="farmSize" id="farmSize" placeholder="e.g., 500" class="border border-gray-600 bg-gray-900 text-gray-100 p-3 rounded w-full" required>
                </div>
                <div>
                  <label class="block text-gray-200 font-semibold mb-1" for="crops">Crops Grown</label>
                  <input type="text" name="crops" id="crops" placeholder="e.g., Corn, Soybeans, Wheat" class="border border-gray-600 bg-gray-900 text-gray-100 p-3 rounded w-full" required>
                </div>
              </div>
              <div>
                <label class="block text-gray-200 font-semibold mb-1" for="challenges">Your Challenges</label>
                <textarea name="challenges" id="challenges" rows="3" placeholder="Describe your main challenges, e.g., 'pest control for corn' or 'managing irrigation during dry seasons'." class="border border-gray-600 bg-gray-900 text-gray-100 p-3 rounded w-full" required></textarea>
              </div>
              <p class="text-xs text-gray-400 mb-2">The more detail you provide, the better the recommendations.</p>
              <button type="submit" class="px-6 py-3 bg-lime-500 hover:bg-lime-600 text-white rounded font-semibold w-full flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m4 0h-1v-4h-1m4 0h-1v-4h-1m4 0h-1v-4h-1" /></svg>
                Get AI Recommendations
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>

    <!-- Modal Popup -->
    <?php if ($show_modal): ?>
      <div id="recommendation-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60">
        <div class="bg-gray-900 rounded-2xl shadow-xl max-w-2xl w-full mx-4 p-8  relative">
          <div class="flex items-center gap-2 mb-2">
            <span class="text-lime-400 text-2xl">🤖</span>
            <h3 class="font-bold text-xl text-white">Your Personalized Tech Recommendations</h3>
          </div>
          <p class="text-gray-400 mb-4">Based on your farm's details, here are some technologies that could help.</p>
          <div class="max-h-96 overflow-y-auto text-gray-200">
            <?= $recommendation_html ?>
          </div>
          <form method="POST">
            <button type="submit" name="close_modal" class="mt-6 px-6 py-2 bg-lime-500 hover:bg-lime-600 text-white rounded font-semibold float-right">Close</button>
          </form>
        </div>
      </div>
      <script>
        document.body.style.overflow = 'hidden';
      </script>
    <?php endif; ?>
    <?php
    if (isset($_POST['close_modal'])) {
      echo "<script>window.location.href = window.location.pathname;</script>";
      exit;
    }
    ?>

    <!-- Tech Showcase -->
    <section class="p-6 bg-gray-900">
      <div class="max-w-4xl mx-auto text-center mb-8">
        <h2 class="text-3xl font-extrabold text-white mb-2">Explore Farm Technologies</h2>
        <p class="text-gray-300 text-lg">Discover innovative tools and solutions to boost your farm's productivity and sustainability.</p>
      </div>
      <div class="flex flex-wrap items-center gap-3 justify-center mb-8">
        <!-- Search Bar -->
        <form method="GET" class="flex items-center gap-3">
          <input
            type="text"
            name="search"
            value="<?= htmlspecialchars($search_query) ?>"
            placeholder="Search technologies..."
            class="px-4 py-2 rounded-lg border border-gray-600 bg-gray-800 text-gray-100 w-64 focus:outline-none focus:border-lime-400"
          />
          <?php if ($selected_category !== "All"): ?>
            <input type="hidden" name="category" value="<?= htmlspecialchars($selected_category) ?>">
          <?php endif; ?>
          <button type="submit" class="hidden"></button>
        </form>
        <!-- Category Links -->
        <a href="?category=All<?= $search_query ? '&search=' . urlencode($search_query) : '' ?>"
           class="bg-lime-500 text-white rounded-full px-4 py-2 font-semibold flex items-center gap-2 <?= ($selected_category === 'All') ? '' : 'hover:bg-lime-600' ?>">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707l-6.414 6.414A1 1 0 0013 14.414V19a1 1 0 01-1.447.894l-4-2A1 1 0 007 17V14.414a1 1 0 00-.293-.707L3.293 6.707A1 1 0 013 6V4z" /></svg>
          All
        </a>
        <?php foreach ($categories as $cat): ?>
          <?php if ($cat === "All") continue; ?>
          <a href="?category=<?= urlencode($cat) ?><?= $search_query ? '&search=' . urlencode($search_query) : '' ?>"
             class="px-4 py-2 rounded-full border border-gray-600 bg-gray-800 text-gray-100 font-semibold <?= ($selected_category === $cat) ? 'border-lime-400 text-lime-400' : 'hover:border-lime-400 hover:text-lime-400' ?>">
            <?= $cat ?>
          </a>
        <?php endforeach; ?>
      </div>
      <div class="grid md:grid-cols-3 gap-4">
        <?php if (empty($filtered_technologies)): ?>
          <div class="col-span-2 text-center text-gray-400 py-8">
            No technologies found for your search or filter.
          </div>
        <?php else: ?>
          <?php foreach ($filtered_technologies as $tech): ?>
            <div class="p-4 rounded-md bg-gray-800">
              <div class="flex gap-2 items-center mb-2">
                <span class="text-2xl"><?= $tech['icon'] ?></span>
                <div>
                  <h3 class="font-bold text-lg text-white"><?= htmlspecialchars($tech['name']) ?></h3>
                  <span class="px-2 py-1 bg-gray-900 text-lime-400 text-xs rounded"><?= htmlspecialchars($tech['category']) ?></span>
                </div>
              </div>
              <img src="<?= $tech['image'] ?>" alt="<?= htmlspecialchars($tech['name']) ?>" class="w-full h-40 object-cover mb-2 rounded" />
              <p class="text-sm text-gray-300"><?= htmlspecialchars($tech['description']) ?></p>
              <div class="mt-4">
  <a href="<?= $tech['link'] ?>" 
     target="_blank" 
     rel="noopener noreferrer"
     class="px-4 py-2 bg-lime-500 hover:bg-lime-600 text-white rounded-b-lg w-full text-center block">
    Learn More
  </a>
</div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </section>

    <!-- Testimonials -->
<!-- Testimonials -->
<section class="p-6">
  <h2 class="text-4xl font-extrabold text-center text-white mb-2">
    Success Stories from the Field
  </h2>
  <p class="text-center text-gray-400 mb-10 text-lg">
    See how farmers are using technology to transform their operations and increase their yields.
  </p>

  <div class="grid md:grid-cols-3 gap-8 max-w-7xl mx-auto">
    <?php foreach ($testimonials as $t): ?>
      <div class="bg-gray-800 rounded-xl shadow-lg p-6">
        
        <!-- Avatar + Name/Title -->
        <div class="flex items-center space-x-4 mb-4">
          <img 
            src="<?= $t['image'] ?>" 
            alt="<?= htmlspecialchars($t['name']) ?>" 
            class="w-12 h-12 rounded-full"
          />
          <div>
            <p class="font-bold text-white"><?= htmlspecialchars($t['name']) ?></p>
            <p class="text-gray-400 text-sm"><?= htmlspecialchars($t['title']) ?></p>
          </div>
        </div>

        <!-- Quote -->
        <blockquote class="text-gray-300 italic leading-relaxed">
          "<?= htmlspecialchars($t['quote']) ?>"
        </blockquote>
      </div>
    <?php endforeach; ?>
  </div>
</section>


  </main>

  <!-- Footer -->
  <footer class="bg-gray-900  mt-8 text-center p-4 text-xs text-gray-400">
    &copy; <?= date('Y') ?> AgriGrow. All rights reserved.
  </footer>
</body>
</html>