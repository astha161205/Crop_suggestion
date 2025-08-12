<?php
session_start(); // Start the session
require_once 'theme_manager.php';
require_once 'language_manager.php';
$theme = getThemeClasses();

// Database connection for blogs
$host = 'localhost';
$dbname = 'crop';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Fetch latest 3 published blogs
    $stmt = $pdo->prepare("SELECT * FROM blogs WHERE status = 'published' ORDER BY created_at DESC LIMIT 3");
    $stmt->execute();
    $latest_blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $latest_blogs = []; // If database connection fails, show empty array
}
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
    <script src="./language.js"></script>
    <style>
.container {
  display: flex;
  justify-content: center;
  gap: 20px;
  flex-wrap: wrap;
  padding: 20px;
}

.box {
  width: 350px;
  background-color: transparent; /* remove white background */
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
  transition: transform 0.2s ease;
  height: 350px;                /* force equal card heights */
  display: flex;                /* stack image + caption */
  flex-direction: column;
}
.caption {
  color: white;
}
.box:hover {
  transform: translateY(-5px);
}

.box img {
  width: 100%;
  height: 220px;
  object-fit: cover;
  display: block;              /* remove bottom whitespace gap */
}
.home-footer .footer-content {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    gap: 10px; /* space between sections */
}

.home-footer .footer-section {
    min-width: 300px; /* ensures columns don't shrink too much */
}

/* Move only the first column to the right */
.home-footer .footer-content .footer-section:first-child {
    margin-left: 100px; /* adjust as needed */
}

.caption {
  padding: 18px;
  text-align: left;
  display: flex;               /* space title and link */
  flex-direction: column;
  flex: 1;                     /* fill remaining height */
}

.caption h3 {
  font-size: 20px;
  font-weight: bold;
  margin-bottom: 10px;
  color: #333;
  display: -webkit-box;        /* clamp long titles */
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  min-height: 48px;            /* keep consistent height area for title */
}

.caption a {
  text-decoration: none;
  color: white;
  font-weight: bold;
  margin-top: auto;            /* pin link to bottom of caption area */
}

.caption a:hover {
  text-decoration: underline;
}

body {
        margin: 0; /* Removes default body space */
        padding: 0;
    }

</style>
</head>
<body class="font-mono <?php echo $theme['bg']; ?> <?php echo $theme['text']; ?> relative">



<header class="flex justify-between items-center <?php echo $theme['bg_header']; ?> h-15 sticky z-20 border-b-2 <?php echo $theme['border_header']; ?> top-0   pl-3 pr-3">
    <div class="flex gap-2 items-center">
        <a href="./homePage.php" class="flex items-center gap-2">
            <img src="../photos/home/logo.png" alt="logo" class="h-10 w-10 rounded-4xl">
            <h3 class="">AgriGrow</h3>
        </a>
    </div>

    <div class="<?php echo $theme['text_secondary']; ?> flex gap-6 pl-0 pr-4 pt-1 pb-1 ml-auto">
        <a href="./homePage.php" class="<?php echo $theme['hover']; ?>"><?php echo __('home'); ?></a>
        <a href="./SUNSIDIES.php" class="<?php echo $theme['hover']; ?>"><?php echo __('subsidies'); ?></a>
        <a href="./blog.php" class="<?php echo $theme['hover']; ?>"><?php echo __('blog'); ?></a>

        
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
            <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
                <a href="./admin_subsidies.php" class="<?php echo $theme['hover']; ?>"><?php echo __('admin_panel'); ?></a>
                <a href="./logout.php" class="<?php echo $theme['hover']; ?> text-red-400"><?php echo __('logout'); ?></a>
            <?php else: ?>
                <a href="./profile.php" class="<?php echo $theme['hover']; ?>"><?php echo __('profile'); ?></a>
            <?php endif; ?>
        <?php else: ?>
            <a href="./login.php" class="<?php echo $theme['hover']; ?>"><?php echo __('login'); ?></a>
        <?php endif; ?>
    </div>
</header>




    <div class="mt-2 relative h-155 flex flex-col items-center justify-center rounded-2xl ml-5 mr-5 overflow-hidden">
    <video  
        autoplay 
        loop 
        muted 
        playsinline
        class="absolute z-0 w-full h-full object-cover"
    >

        <source src="../photos/home/8333971-uhd_4096_2160_25fps.mp4" type="video/mp4">
    </video>

        <div class=" relative z-10 flex flex-col items-center justify-center w-200 text-white">
            <h1 class="text-5xl font-bold text-center mb-8">
                Sustainable farming<br> for a healthier planet
            </h1>
            <p class="text-xl font-bold text-center">
                Empowering farmers with smart, eco-friendly practices to boost crop yield while protecting the environment.
                    Get personalized crop recommendations based on your soil and weather conditions.
                    Together, let’s grow more with less and build a greener tomorrow.
            </p>
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                <p class="bg-lime-500 px-6 py-2 rounded-2xl font-bold mt-20 inline-block">
                   Welcome <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                </p>
            <?php else: ?>
            <a href="login.php" class="bg-lime-500 px-6 py-2 rounded-2xl font-bold mt-25 cursor-pointer inline-block">
                Get Started
            </a>
            <?php endif; ?>
        </div>
    </div>




    <div class="flex flex-col items-center px-4 md:px-0">
  <!-- Services Header -->
  <div class="flex flex-col items-center mt-20 mb-10 text-center max-w-4xl">
    <h1 class="text-4xl font-bold <?php echo $theme['text']; ?>"><?php echo __('our_services'); ?></h1>
    <p class="<?php echo $theme['text_secondary']; ?> mt-4 text-sm md:text-base">
      <?php echo __('services_description'); ?>
    </p>
  </div>

  <!-- Services Cards -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto w-full">
    <!-- Crop Recommendation Card -->
    <a href="./crop_recom.php" class="block">
      <div class="rounded-xl border-2 <?php echo $theme['border']; ?> <?php echo $theme['bg_card']; ?> min-h-[280px] flex flex-col hover:scale-105 transition-transform duration-300 ease-in-out">
        <img src="../photos/home/crop_rec.png" alt="crop" style="height: 280px; width: 100%; object-fit: cover; border-radius: 0.75rem 0.75rem 0 0;">
        <div class="p-4 flex flex-col flex-grow">
          <h3 class="text-lg font-bold mb-2 <?php echo $theme['text']; ?>"><?php echo __('crop_recommendation'); ?></h3>
          <p class="text-sm <?php echo $theme['text_secondary']; ?>">
            <?php echo __('crop_recommendation_desc'); ?>
          </p>
        </div>
      </div>
    </a>

    <!-- Weather Predictions Card -->
    <a href="./weather.php" class="block">
      <div class="rounded-xl border-2 <?php echo $theme['border']; ?> <?php echo $theme['bg_card']; ?> min-h-[280px] flex flex-col hover:scale-105 transition-transform duration-300 ease-in-out">
        <img src="../photos/home/3ZRAI3Y3EBFF7NGTSBCIS7EGRI.avif" alt="weather" style="height: 280px; width: 100%; object-fit: cover; border-radius: 0.75rem 0.75rem 0 0;">
        <div class="p-4 flex flex-col flex-grow">
          <h3 class="text-lg font-bold mb-2 <?php echo $theme['text']; ?>"><?php echo __('weather_forecast'); ?></h3>
          <p class="text-sm <?php echo $theme['text_secondary']; ?>">
            <?php echo __('weather_forecast_desc'); ?>
          </p>
        </div>
      </div>
    </a>

    <!-- Pest and Disease Card -->
    <a href="./pest.php" class="block">
      <div class="rounded-xl border-2 <?php echo $theme['border']; ?> <?php echo $theme['bg_card']; ?> min-h-[280px] flex flex-col hover:scale-105 transition-transform duration-300 ease-in-out">
        <img src="../photos/home/pest.webp" alt="pest" style="height: 280px; width: 100%; object-fit: cover; border-radius: 0.75rem 0.75rem 0 0;">
        <div class="p-4 flex flex-col flex-grow">
          <h3 class="text-lg font-bold mb-2 <?php echo $theme['text']; ?>"><?php echo __('Pest Management Chatbot'); ?></h3>
          <p class="text-sm <?php echo $theme['text_secondary']; ?>">
            <?php echo __('pest_management_desc'); ?>
          </p>
        </div>
      </div>
    </a>

    <!-- Government Schemes Card -->
    <a href="./SUNSIDIES.php" class="block">
      <div class="rounded-xl border-2 <?php echo $theme['border']; ?> <?php echo $theme['bg_card']; ?> min-h-[280px] flex flex-col hover:scale-105 transition-transform duration-300 ease-in-out">
        <img src="../photos/home/subsidies.png" alt="subsidies" style="height: 280px; width: 100%; object-fit: cover; border-radius: 0.75rem 0.75rem 0 0;">
        <div class="p-4 flex flex-col flex-grow">
          <h3 class="text-lg font-bold mb-2 <?php echo $theme['text']; ?>"><?php echo __(' Government Subsidies'); ?></h3>
          <p class="text-sm <?php echo $theme['text_secondary']; ?>">
            <?php echo __('discove and apply for government agriculture subsidies to Empower Your Farming Journey'); ?>
            
          </p>
        </div>
      </div>
    </a>
  </div>
</div>





    <div class="partner <?php echo $theme['bg_card']; ?> px-8 py-10 mt-10 max-w-4xl mx-auto rounded-2xl text-center">
    
    <!-- Heading -->
    <h1 class="text-3xl p-4 font-bold">
        Benefits <span class="font-normal">to be partnered with us</span>
    </h1>
    
    <!-- Description -->
    <p class="text-lg mb-2">
        Partner with us to access advanced agricultural technologies and expert support.
    </p>

    <!-- Benefits Grid -->
    <div class="flex justify-center gap-12 flex-wrap">
        
        <!-- Farm Tech -->
        <a href="./farmtech.php" class="bg-gray-800  p-6 rounded-xl flex flex-col items-center w-56 transform transition-transform hover:scale-110">
            <img src="../photos/home/farm_tech.svg" alt="tech" class="h-32 w-32 mb-4">
            <p class="text-lg font-medium">farm_tech</p>
        </a>

        <!-- Support -->
        <a href="./support.php" class="bg-gray-800  p-6 rounded-xl flex flex-col items-center w-56 transform transition-transform hover:scale-110">
            <img src="../photos/home/support.svg" alt="support" class="h-32 w-32 mb-4">
            <p class="text-lg font-medium">Support</p>
        </a>
        <!-- Feedback -->
        <a href="./feedback.php" class="bg-gray-800  p-6 rounded-xl flex flex-col items-center w-56 transform transition-transform hover:scale-110">
            <img src="../photos/home/feed.webp" alt="feedback" class="h-32 w-32 mb-4">
            <p class="text-lg font-medium">Feedback</p>
        </a>

    </div>
</div>



<div id="blogs" class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4">

        <!-- Heading -->
        <h2 class="text-4xl font-bold text-center mb-4">
            People's Latest <span class="text-green-600">Blogs</span>
        </h2>
        <p class=" text-center mt-2 max-w-2xl mx-auto">
            Discover insights on sustainable farming, investment opportunities, and industry trends.
            Explore some of the latest articles for expert advice and practical tips.
        </p>

        <div style="text-align: center; margin-top: 24px; margin-bottom: 8px;">
    <button
        type="button"
        onclick="window.location.href='./write_blog.php'"
        style="
            background-color: #65a30d; /* lime-600 */
            color: white;
            font-weight: bold;
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            box-shadow: 0px 4px 6px rgba(0,0,0,0.2);
            display: inline-flex;
            align-items: center;
            font-size: 16px;
            transition: all 0.3s ease;
        "
        onmouseover="this.style.backgroundColor='#4d7c0f'"
        onmouseout="this.style.backgroundColor='#65a30d'"
    >
        <i class="fas fa-pen-fancy" style="margin-right: 8px;"></i>
        Write Your Own Blog
    </button>
    <button
        type="button"
        onclick="window.location.href='./blog.php'"
        style="
            background-color: #65a30d; /* lime-600 */
            color: white;
            font-weight: bold;
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            box-shadow: 0px 4px 6px rgba(0,0,0,0.2);
            display: inline-flex;
            align-items: center;
            font-size: 16px;
            transition: all 0.3s ease;
        "
        onmouseover="this.style.backgroundColor='#4d7c0f'"
        onmouseout="this.style.backgroundColor='#65a30d'"
    >
        <i class="fas fa-pen-fancy" style="margin-right: 8px;"></i>
        Read More Blogs
    </button>
</div>

        <div class="container">
            <?php if (empty($latest_blogs)): ?>
                <!-- Show default blogs if no blogs in database -->
                <div class="box">
                    <img src="../photos/home/box1.jpg" alt="Image 1">
                    <div class="caption bg-gray-800 text-white" style="color: #fff">
                        <h3 style="color: white">Investing In The Green Revolution: Growagros And Sustainable Agriculture</h3>
                        <a href="https://www.growagros.com/blog/investing-in-the-green-revolution-growagros-and-sustainable-agriculture/">Read More »</a>
                    </div>
                </div>

                <div class="box">
                    <img src="../photos/home/box2.webp" alt="Image 2">
                    <div class="caption bg-gray-800" style="color: #fff">
                        <h3 style="color: white">Everything You Need To Know About Agricultural Land Lease In India</h3>
                        <a href="https://www.growagros.com/blog/everything-you-need-to-know-about-agricultural-land-lease-in-india/">Read More »</a>
                    </div>
                </div>

                <div class="box">
                    <img src="../photos/home/box3.jpg" alt="Image 3">
                    <div class="caption bg-gray-800" style="color: #fff">
                        <h3 style="color: white">Agricultural Technologies and Innovative Advanced Ways of Farming</h3>
                        <a href="https://www.growagros.com/blog/agricultural-technologies-advanced-ways-of-farming/">Read More »</a>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($latest_blogs as $blog): ?>
                    <div class="box">
                        <img src="<?php echo htmlspecialchars($blog['cover_image_url']); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>">
                        <div class="caption bg-gray-800 text-white" style="color: #fff">
                            <h3 style="color: white"><?php echo htmlspecialchars($blog['title']); ?></h3>
                            <a href="./view_blog.php?id=<?php echo $blog['id']; ?>">Read More »</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

</div>
</div>

<footer class="home-footer bg-gray-800 text-white py-6">
    <div class="footer-content container mx-auto">
        
        <!-- Quick Links -->
        <div class="footer-section">
            <h3 class="text-lg font-bold mb-3"><?php echo __('quick_links'); ?></h3>
            <ul class="footer-links space-y-2">
                <li><a href="./homePage.php" class="flex items-center gap-3 hover:underline">
                    <img src="../photos/home/home-1-svgrepo-com.svg" alt="" class="h-4 w-3"><?php echo __('home'); ?>
                </a></li>
                <li><a href="./blog.php" class="flex items-center gap-3 hover:underline">
                    <img src="../photos/home/blog-svgrepo-com.svg" alt="" class="h-4 w-3"><?php echo __('blog'); ?>
                </a></li>
                <li><a href="./SUNSIDIES.php" class="flex items-center gap-3 hover:underline">
                    <img src="../photos/home/about.svg" alt="" class="h-4 w-4"><?php echo __('Subsidies'); ?>
                </a></li>
            </ul>
        </div>

        <!-- Our Services -->
        <div class="footer-section">
            <h3 class="text-lg font-bold mb-3"><?php echo __('our_services'); ?></h3>
            <ul class="footer-links space-y-2">
                <li><a href="./crop_recom.php" class="hover:underline"><?php echo __('crop_recommendation'); ?></a></li>
                <li><a href="./weather.php" class="hover:underline"><?php echo __('weather_alerts'); ?></a></li>
                <li><a href="./pest.php" class="hover:underline"><?php echo __('pest_management'); ?></a></li>
            </ul>
        </div>

        <!-- Social Links -->
        <div class="footer-section">
            <h3 class="text-lg font-bold mb-3"><?php echo __('social_links'); ?></h3>
            <ul class="footer-links space-y-2">
                <li><a href="https://facebook.com/agrigrow" target="_blank" class="flex items-center gap-2 hover:underline">
                    <img src="../photos/home/facebook.svg" alt="" class="h-4 w-4">Facebook
                </a></li>
                <li><a href="https://instagram.com/agrigrow" target="_blank" class="flex items-center gap-2 hover:underline">
                    <img src="../photos/home/insta.svg" alt="" class="h-4 w-4">Instagram
                </a></li>
                <li><a href="https://twitter.com/agrigrow" target="_blank" class="flex items-center gap-2 hover:underline">
                    <img src="../photos/home/twitter.svg" alt="" class="h-4 w-4">Twitter
                </a></li>
            </ul>
        </div>

    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom text-center p-2 border-t border-gray-600 pt-4">
        <p class="m-0">&copy; 2025 AgriGrow. All rights reserved.</p>
    </div>
</footer>



    <script src="./theme.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
        const accordionItems = document.querySelectorAll('.accordion-item');
    
        accordionItems.forEach(item => {
            const header = item.querySelector('.flex.justify-between');
            const content = item.querySelector('.accordion-content');
            const arrow = item.querySelector('img');
    
            header.addEventListener('click', () => {
                // Close all other items
                accordionItems.forEach(otherItem => {
                    if (otherItem !== item) {
                        otherItem.querySelector('.accordion-content').style.maxHeight = null;
                        otherItem.querySelector('img').classList.remove('rotate-180');
                    }
                });
    
                // Toggle current item
                content.style.maxHeight = content.style.maxHeight ? null : `${content.scrollHeight}px`;
                arrow.classList.toggle('rotate-180');
                
                // Smooth scroll
                if (content.style.maxHeight) {
                    item.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            });
        });
    });

    const menuButton = document.getElementById('menu-btn');
const profileMenu = document.getElementById('profile-menu');

menuButton.addEventListener('click', (e) => {
    e.stopPropagation();
    profileMenu.classList.toggle('active');
});

// Close menu when clicking outside
document.addEventListener('click', (e) => {
    if (!profileMenu.contains(e.target) && !menuButton.contains(e.target)) {
        profileMenu.classList.remove('active');
    }
});
document.addEventListener("DOMContentLoaded", function () {
    const cards = document.querySelectorAll('.hover\\:shadow-lg');
    cards.forEach((card, index) => {
        card.style.opacity = 0;
        card.style.transform = "translateY(30px)";
        setTimeout(() => {
            card.style.transition = "all 0.6s ease";
            card.style.opacity = 1;
            card.style.transform = "translateY(0)";
        }, index * 200);
    });
});
        </script>
        
</body>
</html>