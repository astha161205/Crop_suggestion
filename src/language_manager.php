<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Language translations
$translations = [
    'en' => [
        // Navigation
        'home' => 'Home',
        'subsidies' => 'Subsidies',
        'blog' => 'Blog',
        'about_us' => 'About us',
        'login' => 'Login',
        'logout' => 'Logout',
        'profile' => 'Profile',
        'admin_panel' => 'Admin Panel',
       
        
        // Footer
        'join_agrigrow_message' => 'अपनी कृषि प्रथाओं में क्रांति लाने और एक हरित, अधिक सतत भविष्य में योगदान करने के लिए AgriGrow में शामिल हों।',
        'quick_links' => 'त्वरित लिंक',
        'social_links' => 'सामाजिक लिंक',
        'crop_recommendation' => 'फसल सिफारिश',
        'weather_alerts' => 'मौसम चेतावनी'
    ]
];

// Get current language from session or default to English
function getCurrentLanguage() {
    return $_SESSION['language'] ?? 'en';
}

// Set language
function setLanguage($language) {
    if (in_array($language, ['en', 'hi'])) {
        $_SESSION['language'] = $language;
        return true;
    }
    return false;
}

// Get translation
function __($key) {
    global $translations;
    $lang = getCurrentLanguage();
    
    if (isset($translations[$lang][$key])) {
        return $translations[$lang][$key];
    }
    
    // Fallback to English if translation not found
    if (isset($translations['en'][$key])) {
        return $translations['en'][$key];
    }
    
    // Return key if no translation found
    return $key;
}

// Handle language toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_language'])) {
    $current_lang = getCurrentLanguage();
    $new_lang = $current_lang === 'en' ? 'hi' : 'en';
    setLanguage($new_lang);
    
    
    // Redirect back to the page that made the request
    $_SESSION['redirect_after_language_toggle'] = $_POST['redirect_url'] ?? 'index.php';
    exit();
}

// Get language display name
function getLanguageDisplayName($lang) {
    $names = [
        'en' => 'English',
        'hi' => 'हिंदी'
    ];
    return $names[$lang] ?? $lang;
}