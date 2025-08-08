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
  background-color: #fff;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s ease;
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
}

.caption {
  padding: 18px;
  text-align: left;
}

.caption h3 {
  font-size: 20px;
  font-weight: bold;
  margin-bottom: 10px;
  color: #333;
}

.caption a {
  text-decoration: none;
  color: white;
  font-weight: bold;
}

.caption a:hover {
  text-decoration: underline;
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
    </div>
</div>



<div id="blogs" class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4">

        <!-- Heading -->
        <h2 class="text-4xl font-bold text-center mb-4">
            Our Latest <span class="text-green-600">Blogs</span>
        </h2>
        <p class=" text-center mt-2 max-w-2xl mx-auto">
            Discover insights on sustainable farming, investment opportunities, and industry trends.
            Explore our latest articles for expert advice and practical tips.
        </p>

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

    <div class="flex items-center mt-20 gap-10 justify-between ml-10 mr-10 ">

        

        <div class="Quick_link  flex flex-col" >
            <h1><?php echo __('quick_links'); ?></h1>
            <div class="flex flex-col gap-2 mt-3">
                <a href="./homePage.php" class="flex items-center gap-3"><img src="../photos/home/home-1-svgrepo-com.svg" alt="" class="h-4 w-3"><?php echo __('home'); ?></a>
                <a href="./blog.php" class="flex items-center gap-3"><img src="../photos/home/blog-svgrepo-com.svg" alt="" class="h-4 w-3"><?php echo __('blog'); ?></a>
                <a href="./SUNSIDIES.php" class="flex items-center gap-3"><img src="../photos/home/about.svg" alt="" class="h-4 w-4"><?php echo __('Subsidies'); ?></a>
            </div>
        </div>

        <div class="Services_link  flex flex-col" >
            <h1 class="text-center"><?php echo __('our_services'); ?></h1>
            <div class="flex flex-col gap-2 mt-3">
                <a href="./crop_recom.php"><?php echo __('crop_recommendation'); ?></a>
                <a href="./weather.php"><?php echo __('weather_alerts'); ?></a>
                <a href="./pest.php"><?php echo __('pest_management'); ?></a>
            </div>
        </div>

        <div class="Social_link  flex flex-col" >
            <h1><?php echo __('social_links'); ?></h1>
            <div class="flex flex-col gap-2 mt-3">
                <a href="https://facebook.com/agrigrow"  target="_blank"  class="flex gap-2 items-center"><img src="../photos/home/facebook.svg" alt="" class="h-4 w-4">Facebook</a>
                <a href="https://instagram.com/agrigrow" target="_blank" class="flex gap-2 items-center"><img src="../photos/home/insta.svg" alt="" class="h-4 w-4">Instagram</a>
                <a href="https://twitter.com/agrigrow"   target="_blank"  class="flex gap-2 items-center"><img src="../photos/home/twitter.svg" alt="" class="h-4 w-4">Twitter</a>
                <!-- <a href="https://www.linkedin.com/muwahidmir"  target="_blank"  class="flex gap-2 items-center"><img src="../photos/home/linkedin.svg" alt="" class="h-4 w-4">LinkedIn</a> -->
            </div>
        </div>
        
    </div>
    
    
</div>
    <footer class="  <?php echo $theme['bg_card']; ?>    w-full">
        <div class="flex  ">
            <p class="p-2">© 2025 AgriGrow. <?php echo __('all_rights_reserved'); ?></p>
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