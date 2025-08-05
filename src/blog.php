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
            <h3 class="">AgriGrow</h3>
        </a>
    </div>

    <div class="text-gray-400 flex items-center gap-5 border-2 border-gray-800 rounded-2xl pl-4 pr-4 pt-1 pb-1">
        <a href="./homePage.php" class="hover:text-white">Home</a>
        <a href="./SUNSIDIES.php" class="hover:text-white">Subsidies</a>
        <a href="./blog.php" class="hover:text-white">Blog</a>
        <a href="./homePage.php#About" class="hover:text-white">About us</a>
    </div>

    <div class="relative">
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
            <div class="flex items-center gap-2">
                <button id="menu-btn" class="p-2 hover:bg-gray-800 rounded-lg transition-colors flex items-center gap-2">
                    <span class="text-white"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                    <i class="fa-solid fa-caret-down text-white text-sm"></i>
                </button>
            
                <div id="profile-menu" class="hidden absolute  right-0 mt-10 top-10 w-48 bg-gray-800 rounded-lg shadow-xl py-2">
                    <span class="block px-4 py-2 text-gray-400 cursor-default"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                    <a href="./logout.php" class="block px-4 py-2 text-white hover:bg-gray-700">Logout</a>
                </div>
            </div>
        <?php else: ?>
            <a href="./login.php" class="p-2 bg-lime-500 text-white rounded-lg hover:bg-lime-600 transition-colors">Login</a>
        <?php endif; ?>
    </div>
</header>

    <main class="container mx-auto px-4 py-12 max-w-4xl">
        <!-- Blog 1 - Full Width -->
        <article class="bg-gray-900 rounded-2xl p-8 mb-16">
            <div class="flex items-center text-sm text-gray-400 mb-4">
                <span class="bg-lime-400/20 text-lime-300 px-3 py-1 rounded-full mr-4">Smart Farming</span>
                <span>September 15, 2023</span>
            </div>
            <h1 class="text-3xl font-bold mb-6">Revolutionizing Agriculture with IoT: A Comprehensive Guide</h1>
            <img src="../photos/home/IoT-in-Agriculture.png" alt="IoT in farming" class="w-full h-96 object-cover rounded-xl mb-8">
            
            <div class="space-y-6 text-gray-300">
                <p class="text-lg leading-relaxed">The integration of Internet of Things (IoT) technology in agriculture is transforming traditional farming practices. Smart sensors deployed across fields collect real-time data on soil moisture, temperature, and crop health, enabling farmers to make data-driven decisions.</p>

                <div class="bg-gray-800 p-6 rounded-xl">
                    <h2 class="text-xl font-bold mb-4 text-lime-300">Key IoT Applications:</h2>
                    <ul class="list-disc pl-6 space-y-3">
                        <li>Precision irrigation systems reducing water usage by 40%</li>
                        <li>Livestock monitoring through GPS-enabled collars</li>
                        <li>Automated pest detection using image recognition</li>
                        <li>Predictive analytics for crop yield optimization</li>
                    </ul>
                </div>

                <p class="text-lg leading-relaxed">Recent case studies from California's Central Valley show IoT adoption has increased average yields by 22% while reducing chemical usage by 35%. Farmers can now monitor their fields remotely through mobile apps, receiving instant alerts about potential issues.</p>
            </div>
        </article>

        <!-- Blog 2 - Full Width -->
        <article class="bg-gray-900 rounded-2xl p-8 mb-16">
            <div class="flex items-center text-sm text-gray-400 mb-4">
                <span class="bg-lime-400/20 text-lime-300 px-3 py-1 rounded-full mr-4">Pest Control</span>
                <span>September 12, 2023</span>
            </div>
            <h1 class="text-3xl font-bold mb-6">Integrated Pest Management: Sustainable Solutions for Modern Farms</h1>
            <img src="../photos/home/Pest-management.jpg" alt="Pest management" class="w-full h-96 object-cover rounded-xl mb-8">
            
            <div class="space-y-6 text-gray-300">
                <p class="text-lg leading-relaxed">Traditional pesticide reliance is being replaced by Integrated Pest Management (IPM) strategies that combine biological, cultural, and mechanical controls. This approach reduces chemical use by 50-75% while maintaining crop protection.</p>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-gray-800 p-6 rounded-xl">
                        <h3 class="text-lg font-bold mb-3 text-lime-300">Biological Controls</h3>
                        <ul class="space-y-2">
                            <li>→ Ladybugs for aphid control</li>
                            <li>→ Parasitic wasps against caterpillars</li>
                            <li>→ Nematodes for soil-borne pests</li>
                        </ul>
                    </div>
                    <div class="bg-gray-800 p-6 rounded-xl">
                        <h3 class="text-lg font-bold mb-3 text-lime-300">Cultural Practices</h3>
                        <ul class="space-y-2">
                            <li>→ Crop rotation strategies</li>
                            <li>→ Trap cropping techniques</li>
                            <li>→ Resistant variety selection</li>
                        </ul>
                    </div>
                </div>

                <p class="text-lg leading-relaxed">A 2023 USDA study showed farms implementing IPM strategies saw 28% higher profitability due to reduced input costs and premium organic pricing. Mobile apps like PestWeb now help farmers identify pests through AI-powered image recognition.</p>
            </div>
        </article>

        <!-- Blog 3 - Full Width -->
        <article class="bg-gray-900 rounded-2xl p-8 mb-16">
            <div class="flex items-center text-sm text-gray-400 mb-4">
                <span class="bg-lime-400/20 text-lime-300 px-3 py-1 rounded-full mr-4">Agri-Tech</span>
                <span>September 10, 2023</span>
            </div>
            <h1 class="text-3xl font-bold mb-6">Drone Technology in Precision Agriculture: 2023 Market Report</h1>
            <img src="../photos/home/Drone.jpg" alt="Agricultural drones" class="w-full h-96 object-cover rounded-xl mb-8">
            
            <div class="space-y-6 text-gray-300">
                <p class="text-lg leading-relaxed">Agricultural drone usage has skyrocketed 300% since 2020, with the global market expected to reach $9.6 billion by 2027. Modern drones equipped with multispectral sensors provide crucial data for:</p>

                <div class="bg-gray-800 p-6 rounded-xl">
                    <div class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-[200px]">
                            <h3 class="text-lg font-bold text-lime-300 mb-2">Top Applications</h3>
                            <ul class="space-y-2">
                                <li>→ Crop health monitoring</li>
                                <li>→ Precision spraying</li>
                                <li>→ Planting optimization</li>
                            </ul>
                        </div>
                        <div class="flex-1 min-w-[200px]">
                            <h3 class="text-lg font-bold text-lime-300 mb-2">Key Benefits</h3>
                            <ul class="space-y-2">
                                <li>→ 90% chemical reduction</li>
                                <li>→ 60% faster field analysis</li>
                                <li>→ $127/acre cost savings</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <p class="text-lg leading-relaxed">New AI-powered drones like the DJI Agras T40 can autonomously map 500-acre fields in 90 minutes, while variable-rate spraying systems reduce herbicide use by targeting weeds with millimeter precision.</p>
            </div>
        </article>

    </main>

    <footer class=" bg-gray-900  mt-5  w-full">
        <div class="flex justify-center items-center ">
            <p>© 2021 AgriGrow. All rights reserved</p>
        </div>
    </footer>

</body>
</html>