<?php
// header.php

// Define common page links here
$homePage = "index.php"; // Change this once and it applies everywhere
$subsidiesPage = "SUNSIDIES.php";
$blogPage = "blog.php";
$profilePage = "profile.php";
$loginPage = "login.php";
?>

<header class="flex justify-between items-center bg-gray-950 h-15 sticky z-20 border-b-2 border-b-gray-900 top-0 pl-3 pr-3">
    <div class="flex gap-2 items-center">
        <a href="<?php echo $homePage; ?>" class="flex items-center gap-2">
            <img src="./home/logo.png" alt="logo" class="h-10 w-10 rounded-4xl">
            <h3>AgriGrow</h3>
        </a>
    </div>
    <div class="text-gray-400 flex gap-6 pl-5 pr-4 pt-1 pb-1 ml-auto">
        <a href="<?php echo $homePage; ?>" class="hover:text-white">Home</a>
        <a href="<?php echo $subsidiesPage; ?>" class="hover:text-white">Subsidies</a>
        <a href="<?php echo $blogPage; ?>" class="hover:text-white">Blog</a>
        
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
            <a href="<?php echo $profilePage; ?>" class="hover:text-white">Profile</a>
        <?php else: ?>
            <a href="<?php echo $loginPage; ?>" class="hover:text-white">Login</a>
        <?php endif; ?>
    </div>
</header>
