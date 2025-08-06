<?php
session_start(); // Start the session
require_once 'theme_manager.php';
require_once 'language_manager.php';
$theme = getThemeClasses();
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

</head>
<body class="font-mono <?php echo $theme['bg']; ?> <?php echo $theme['text']; ?> relative">



<header class="flex justify-between items-center <?php echo $theme['bg_header']; ?> h-15 sticky z-20 border-b-2 <?php echo $theme['border_header']; ?> top-0 pl-3 pr-3">
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
        <img src="../photos/home/service_crop-recomendation2.jpg" alt="crop" class="h-45 w-full rounded-t-xl object-cover">
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
        <img src="../photos/home/3ZRAI3Y3EBFF7NGTSBCIS7EGRI.avif" alt="weather" class="h-45 w-full rounded-t-xl object-cover">
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
        <img src="../photos/home/service_pesticidesjpg.jpg" alt="pest" class="h-45 w-full rounded-t-xl object-cover">
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
        <img src="../photos/home/1713762716-these-government-schemes-for-farmers-in-madhya-pradesh-madhya-pradesh-scheme-2024.jpg" alt="subsidies" class="h-55 w-full rounded-t-xl object-cover">
        <div class="p-4 flex flex-col flex-grow">
          <h3 class="text-lg font-bold mb-3 <?php echo $theme['text']; ?>"><?php echo __(' Government Subsidies'); ?></h3>
          <p class="text-sm <?php echo $theme['text_secondary']; ?>">
            <?php echo __('discove and apply for government agriculture subsidies to Empower Your Farming Journey'); ?>
            
          </p>
        </div>
      </div>
    </a>
  </div>
</div>





    <div class="partner  <?php echo $theme['bg_card']; ?> px-6 py-5 mt-25 max-w-5xl mx-auto rounded-2xl flex flex-col items-center justify-center">
    <div class="w-full flex flex-col p-3 ">
            <h1><strong class="text-2xl">Benifits</strong> to be partnered with us</h1>
            <p>Partner with us to access advanced agricultural technologies, expert support, and a community dedicated to sustainable farming.</p>
            <div class="partner_img flex justify-between gap-5 mt-10">
                <img src="../photos/home/farm_tech.svg" alt="tech" class="h-25 w-30 rounded-2xl">
                <img src="../photos/home/support.svg" alt="partner2" class="h-25 w-30 rounded-2xl">
                <img src="../photos/home/sustainable.svg" alt="partner3" class="h-25 w-30 rounded-2xl rotate-image">
                <img src="../photos/home/community.svg" alt="partner4" class="h-25 w-30 rounded-2xl">
            </div>
            <div class="partner_img flex justify-between gap-5 mt-3">
                <a href="./farmtech.php"><p class="w-30 text-center ">farm_tech </p></a>
                <a href="./support.php"><p class="w-30 text-center ">Support </p></a>
                <a href="./sustainable.php"><p class="w-30 text-center ">Sustainable </p></a>
                <a href="./community.php"><p class="w-30 text-center ">community </p></a>
                
            </div>
        </div>

    </div>





    <div class="flex flex-col items-center mt-25" >
        <h1 class="text-3xl font-bold mb-4">
            Our Farming Methodology
        </h1>
        <p class="w-150 text-center mb-8">
            Innovative agricultural practices combining technology and sustainability
        </p>
        <div class="relative flex gap-8 items-start">
            <img src="../photos/home/tractor-spray.jpg" alt="farming-methods" 
                 class="h-110 w-150 rounded-xl object-cover sticky top-4">
    
            <div class="flex flex-col justify-between gap-2 h-110 overflow-y-auto w-120 pr-2 hover:pr-0 transition-all duration-300" 
     id="accordion-container">
                <!-- Precision Agriculture -->
                <div class="accordion-item border-2  <?php echo $theme['bg_card']; ?>0 rounded-xl  <?php echo $theme['bg_card']; ?>">
                    <div class="accordion-header flex justify-between items-center p-4 cursor-pointer">
                        <h3 class="text-lg font-semibold">Precision Agriculture</h3>
                        <img src="../photos/home/down.svg" class="h-5 w-5 transition-transform accordion-arrow">
                    </div>
                    <div class="accordion-content px-4 pb-4 space-y-3">
                        <ul class="list-disc pl-6 text-gray-300 space-y-2">
                            <li>Real-time soil moisture monitoring</li>
                            <li>Crop health imaging (NDVI analysis)</li>
                            <li>Variable-rate fertilizer application</li>
                            <li>Automated yield mapping</li>
                        </ul>
                    </div>
                </div>
    
                <!-- Soil Health Optimization -->
                <div class="accordion-item border-2  <?php echo $theme['bg_card']; ?> rounded-xl  <?php echo $theme['bg_card']; ?>">
                    <div class="accordion-header flex justify-between items-center p-4 cursor-pointer">
                        <h3 class="text-lg font-semibold">Soil Health Optimization</h3>
                        <img src="../photos/home/down.svg" class="h-5 w-5 transition-transform accordion-arrow">
                    </div>
                    <div class="accordion-content px-4 pb-4 space-y-3">
                        <div class="grid grid-cols-2 gap-3 text-gray-300">
                            <div class=" <?php echo $theme['bg_card']; ?> p-3 rounded-lg">
                                <h4 class="text-lime-300 text-sm font-semibold">Cover Cropping</h4>
                                <p class="text-xs mt-1">Legume rotations adding 50kg N/ha</p>
                            </div>
                            <div class=" <?php echo $theme['bg_card']; ?>p-3 rounded-lg">
                                <h4 class="text-lime-300 text-sm font-semibold">Biochar Amendment</h4>
                                <p class="text-xs mt-1">20% increase in water retention</p>
                            </div>
                        </div>
                    </div>
                </div>
    
                <!-- Smart Irrigation -->
                <div class="accordion-item border-2  <?php echo $theme['bg_card']; ?> rounded-xl  <?php echo $theme['bg_card']; ?>">
                    <div class="accordion-header flex justify-between items-center p-4 cursor-pointer">
                        <h3 class="text-lg font-semibold">Smart Irrigation</h3>
                        <img src="../photos/home/down.svg" class="h-5 w-5 transition-transform accordion-arrow">
                    </div>
                    <div class="accordion-content px-4 pb-4 space-y-3">
                        <ul class="list-disc pl-6 text-gray-300 space-y-2">
                            <li>Soil tension monitoring</li>
                            <li>Evapotranspiration calculations</li>
                            <li>Drip irrigation automation</li>
                        </ul>
                    </div>
                </div>
    
                <!-- Eco-Pest Management -->
                <div class="accordion-item border-2 border-gray-800 rounded-xl  <?php echo $theme['bg_card']; ?>">
                    <div class="accordion-header flex justify-between items-center p-4 cursor-pointer">
                        <h3 class="text-lg font-semibold">Eco-Pest Management</h3>
                        <img src="../photos/home/down.svg" class="h-5 w-5 transition-transform accordion-arrow">
                    </div>
                    <div class="accordion-content px-4 pb-4 space-y-3">
                        <div class="grid grid-cols-2 gap-3 text-gray-300">
                            <div class=" <?php echo $theme['bg_card']; ?> p-3 rounded-lg">
                                <h4 class="text-lime-300 text-sm font-semibold">Biological Controls</h4>
                                <ul class="text-xs list-disc pl-4 mt-1 space-y-1">
                                    <li>Ladybugs for aphids</li>
                                    <li>Nematodes for grubs</li>
                                </ul>
                            </div>
                            <div class=" <?php echo $theme['bg_card']; ?> p-3 rounded-lg">
                                <h4 class="text-lime-300 text-sm font-semibold">Monitoring</h4>
                                <ul class="text-xs list-disc pl-4 mt-1 space-y-1">
                                    <li>AI pest recognition</li>
                                    <li>Smart trap networks</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





    



    <div class="flex items-center mt-25 gap-20 justify-between ml-10 mr-10">

        <div class="Agri_pro w-85 flex flex-col " >
            <span class="flex gap-2 items-center">
                <img src="../photos/home/logo.png" alt="logo" class="h-10 w-10 rounded-4xl">
                <h1>AgriGrow</h1>
            </span>
            
        </div>

        <div class="Quick_link  flex flex-col" >
            <h1><?php echo __('quick_links'); ?></h1>
            <div class="flex flex-col gap-2 mt-3">
                <a href="./homePage.php" class="flex items-center gap-3"><img src="../photos/home/home-1-svgrepo-com.svg" alt="" class="h-4 w-3"><?php echo __('home'); ?></a>
                <a href="./blog.php" class="flex items-center gap-3"><img src="../photos/home/blog-svgrepo-com.svg" alt="" class="h-4 w-3"><?php echo __('blog'); ?></a>
                <a href="./homePage.php#About" class="flex items-center gap-3"><img src="../photos/home/about.svg" alt="" class="h-4 w-4"><?php echo __('about_us'); ?></a>
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
    
    <div>

    </div>

    <footer class="  <?php echo $theme['bg_card']; ?>  mt-5  w-full">
        <div class="flex justify-center items-center ">
            <p>© 2021 AgriGrow. <?php echo __('all_rights_reserved'); ?></p>
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
        </script>
        
</body>
</html>