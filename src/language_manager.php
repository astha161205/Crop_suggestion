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
        
        // Hero Section
        'hero_title' => 'Sustainable farming for a healthier planet',
        'hero_subtitle' => 'Empowering farmers with smart, eco-friendly practices to boost crop yield while protecting the environment. Get personalized crop recommendations based on your soil and weather conditions. Together, let\'s grow more with less and build a greener tomorrow.',
        'get_started' => 'Get Started',
        'welcome' => 'Welcome',
        
        // Services
        'our_services' => 'Our Services',
        'services_description' => 'Discover our range of innovative services designed to empower farmers and promote sustainable agriculture. From personalized crop recommendations to advanced weather predictions and pest management solutions, we provide tools to help you grow smarter and more efficiently.',
        
        // Service Cards
        'crop_recommendation' => 'Crop Recommendation',
        'crop_recommendation_desc' => 'Get personalized crop suggestions based on your soil type, climate, and season for optimal yield.',
        'weather_forecast' => 'Weather Forecast',
        'weather_forecast_desc' => 'Access accurate weather predictions to plan your farming activities and protect your crops.',
        'pest_management' => 'Pest Management',
        'pest_management_desc' => 'Learn about effective pest control methods and get recommendations for organic solutions.',
        'farm_technology' => 'Farm Technology',
        'farm_technology_desc' => 'Explore modern farming techniques and technology to improve productivity and sustainability.',
        
        // About Section
        'about_title' => 'About AgriGrow',
        'about_description' => 'AgriGrow is dedicated to revolutionizing agriculture through technology and sustainable practices. We believe in empowering farmers with knowledge and tools to achieve better yields while preserving our environment.',
        
        // Footer
        'contact_us' => 'Contact Us',
        'follow_us' => 'Follow Us',
        'privacy_policy' => 'Privacy Policy',
        'terms_of_service' => 'Terms of Service',
        'all_rights_reserved' => 'All rights reserved.',
        
        // Forms
        'email' => 'Email',
        'password' => 'Password',
        'name' => 'Name',
        'phone' => 'Phone',
        'address' => 'Address',
        'farm_size' => 'Farm Size (acres)',
        'submit' => 'Submit',
        'register' => 'Register',
        'forgot_password' => 'Forgot Password?',
        'dont_have_account' => 'Don\'t have an account?',
        'already_have_account' => 'Already have an account?',
        
        // Messages
        'login_success' => 'Login successful!',
        'login_failed' => 'Login failed. Please check your credentials.',
        'registration_success' => 'Registration successful!',
        'registration_failed' => 'Registration failed. Please try again.',
        'logout_success' => 'Logged out successfully!',
        'email_already_registered' => 'Email already registered!',
        'database_error' => 'Database connection error. Please try again.',
        'fill_all_fields' => 'Please fill all fields!',
        
        // Crop Recommendation
        'soil_type' => 'Soil Type',
        'climate_zone' => 'Climate Zone',
        'season' => 'Season',
        'get_recommendations' => 'Get Recommendations',
        'recommended_crops' => 'Recommended Crops',
        
        // Subsidies
        'available_subsidies' => 'Available Subsidies',
        'apply_now' => 'Apply Now',
        'amount' => 'Amount',
        'eligibility' => 'Eligibility',
        'deadline' => 'Deadline',
        'status' => 'Status',
        
        // Weather
        'current_weather' => 'Current Weather',
        'temperature' => 'Temperature',
        'humidity' => 'Humidity',
        'wind_speed' => 'Wind Speed',
        'forecast' => 'Forecast',
        
        // Theme
        'light_mode' => 'Light Mode',
        'dark_mode' => 'Dark Mode',
        'toggle_theme' => 'Toggle Theme',
        'toggle_language' => 'Toggle Language',
        
        // Profile Page
        'personal_information' => 'Personal Information',
        'farm_information' => 'Farm Information',
        'account_management' => 'Account Management',
        'theme_settings' => 'Theme Settings',
        'language_settings' => 'Language Settings',
        'current_theme' => 'Current Theme',
        'current_language' => 'Current Language',
        'switch_language' => 'Switch Language',
        'language_description' => 'Choose your preferred language for the website interface',
        'edit_profile' => 'Edit Profile',
        'create_profile' => 'Create Profile',
        'update_profile' => 'Update Profile',
        'cancel' => 'Cancel',
        'full_name' => 'Full Name',
        'farm_name' => 'Farm Name',
        'farm_size' => 'Farm Size',
        'location' => 'Location',
        'member_since' => 'Member Since',
        'farmer' => 'Farmer',
        
        // Login Page
        'or_use_email_for_registration' => 'or use your email for registration',
        'or_use_email_and_password' => 'or use your email and password',
        'user' => 'User',
        'admin' => 'Admin',
        'welcome_back' => 'Welcome Back',
        'enter_personal_details' => 'Enter your personal details to use all of site features',
        'hello_friend' => 'Hello, Friend',
        'register_personal_details' => 'Register with your personal details to use all of site features',
        
        // Additional Profile Page
        'manage_account_settings' => 'Manage your account settings and logout from AgriGrow',
        'switch_to' => 'Switch to',
        'my_applications' => 'My Applications',
        'back_to_home' => 'Back to Home',
        'logout_warning' => 'Logging out will end your current session. You\'ll need to login again to access your profile.',
        
        // Footer
        'join_agrigrow_message' => 'Join AgriGrow to revolutionize your farming practices and contribute to a greener, more sustainable future.',
        'quick_links' => 'Quick Links',
        'social_links' => 'Social Links',
        'crop_recommendation' => 'Crop Recommendation',
        'weather_alerts' => 'Weather Alerts'
    ],
    
    'hi' => [
        // Navigation
        'home' => 'होम',
        'subsidies' => 'सब्सिडी',
        'blog' => 'ब्लॉग',
        'about_us' => 'हमारे बारे में',
        'login' => 'लॉगिन',
        'logout' => 'लॉगआउट',
        'profile' => 'प्रोफाइल',
        'admin_panel' => 'एडमिन पैनल',
        
        // Hero Section
        'hero_title' => 'स्वस्थ ग्रह के लिए सतत कृषि',
        'hero_subtitle' => 'किसानों को स्मार्ट, पर्यावरण-अनुकूल प्रथाओं के साथ सशक्त बनाना जो फसल उपज को बढ़ाने के साथ-साथ पर्यावरण की रक्षा भी करते हैं। अपनी मिट्टी और मौसम की स्थिति के आधार पर व्यक्तिगत फसल सिफारिशें प्राप्त करें। आइए एक साथ कम में अधिक उगाएं और हरित कल का निर्माण करें।',
        'get_started' => 'शुरू करें',
        'welcome' => 'स्वागत है',
        
        // Services
        'our_services' => 'हमारी सेवाएं',
        'services_description' => 'किसानों को सशक्त बनाने और सतत कृषि को बढ़ावा देने के लिए डिज़ाइन की गई हमारी नवीन सेवाओं की श्रृंखला की खोज करें। व्यक्तिगत फसल सिफारिशों से लेकर उन्नत मौसम पूर्वानुमान और कीट प्रबंधन समाधानों तक, हम आपको स्मार्ट और अधिक कुशलता से उगाने में मदद करने के लिए उपकरण प्रदान करते हैं।',
        
        // Service Cards
        'crop_recommendation' => 'फसल सिफारिश',
        'crop_recommendation_desc' => 'अपनी मिट्टी के प्रकार, जलवायु और मौसम के आधार पर इष्टतम उपज के लिए व्यक्तिगत फसल सुझाव प्राप्त करें।',
        'weather_forecast' => 'मौसम पूर्वानुमान',
        'weather_forecast_desc' => 'अपनी कृषि गतिविधियों की योजना बनाने और अपनी फसलों की रक्षा के लिए सटीक मौसम पूर्वानुमान प्राप्त करें।',
        'pest_management' => 'कीट प्रबंधन',
        'pest_management_desc' => 'प्रभावी कीट नियंत्रण विधियों के बारे में जानें और जैविक समाधानों के लिए सिफारिशें प्राप्त करें।',
        'farm_technology' => 'कृषि प्रौद्योगिकी',
        'farm_technology_desc' => 'उत्पादकता और स्थिरता में सुधार के लिए आधुनिक कृषि तकनीकों और प्रौद्योगिकी का अन्वेषण करें।',
        
        // About Section
        'about_title' => 'AgriGrow के बारे में',
        'about_description' => 'AgriGrow प्रौद्योगिकी और सतत प्रथाओं के माध्यम से कृषि में क्रांति लाने के लिए समर्पित है। हम किसानों को ज्ञान और उपकरणों के साथ सशक्त बनाने में विश्वास करते हैं ताकि बेहतर उपज प्राप्त कर सकें और हमारे पर्यावरण को संरक्षित कर सकें।',
        
        // Footer
        'contact_us' => 'संपर्क करें',
        'follow_us' => 'हमें फॉलो करें',
        'privacy_policy' => 'गोपनीयता नीति',
        'terms_of_service' => 'सेवा की शर्तें',
        'all_rights_reserved' => 'सर्वाधिकार सुरक्षित।',
        
        // Forms
        'email' => 'ईमेल',
        'password' => 'पासवर्ड',
        'name' => 'नाम',
        'phone' => 'फोन',
        'address' => 'पता',
        'farm_size' => 'खेत का आकार (एकड़)',
        'submit' => 'सबमिट करें',
        'register' => 'रजिस्टर करें',
        'forgot_password' => 'पासवर्ड भूल गए?',
        'dont_have_account' => 'खाता नहीं है?',
        'already_have_account' => 'पहले से खाता है?',
        
        // Messages
        'login_success' => 'लॉगिन सफल!',
        'login_failed' => 'लॉगिन विफल। कृपया अपने क्रेडेंशियल्स जांचें।',
        'registration_success' => 'पंजीकरण सफल!',
        'registration_failed' => 'पंजीकरण विफल। कृपया पुनः प्रयास करें।',
        'logout_success' => 'सफलतापूर्वक लॉगआउट!',
        'email_already_registered' => 'ईमेल पहले से पंजीकृत है!',
        'database_error' => 'डेटाबेस कनेक्शन त्रुटि। कृपया पुनः प्रयास करें।',
        'fill_all_fields' => 'कृपया सभी फील्ड भरें!',
        
        // Crop Recommendation
        'soil_type' => 'मिट्टी का प्रकार',
        'climate_zone' => 'जलवायु क्षेत्र',
        'season' => 'मौसम',
        'get_recommendations' => 'सिफारिशें प्राप्त करें',
        'recommended_crops' => 'अनुशंसित फसलें',
        
        // Subsidies
        'available_subsidies' => 'उपलब्ध सब्सिडी',
        'apply_now' => 'अभी आवेदन करें',
        'amount' => 'राशि',
        'eligibility' => 'पात्रता',
        'deadline' => 'अंतिम तिथि',
        'status' => 'स्थिति',
        
        // Weather
        'current_weather' => 'वर्तमान मौसम',
        'temperature' => 'तापमान',
        'humidity' => 'आर्द्रता',
        'wind_speed' => 'हवा की गति',
        'forecast' => 'पूर्वानुमान',
        
        // Theme
        'light_mode' => 'लाइट मोड',
        'dark_mode' => 'डार्क मोड',
        'toggle_theme' => 'थीम बदलें',
        'toggle_language' => 'भाषा बदलें',
        
        // Profile Page
        'personal_information' => 'व्यक्तिगत जानकारी',
        'farm_information' => 'खेत की जानकारी',
        'account_management' => 'खाता प्रबंधन',
        'theme_settings' => 'थीम सेटिंग्स',
        'language_settings' => 'भाषा सेटिंग्स',
        'current_theme' => 'वर्तमान थीम',
        'current_language' => 'वर्तमान भाषा',
        'switch_language' => 'भाषा बदलें',
        'language_description' => 'वेबसाइट इंटरफेस के लिए अपनी पसंदीदा भाषा चुनें',
        'edit_profile' => 'प्रोफाइल संपादित करें',
        'create_profile' => 'प्रोफाइल बनाएं',
        'update_profile' => 'प्रोफाइल अपडेट करें',
        'cancel' => 'रद्द करें',
        'full_name' => 'पूरा नाम',
        'farm_name' => 'खेत का नाम',
        'farm_size' => 'खेत का आकार',
        'location' => 'स्थान',
        'member_since' => 'सदस्यता से',
        'farmer' => 'किसान',
        
        // Login Page
        'or_use_email_for_registration' => 'या पंजीकरण के लिए अपना ईमेल उपयोग करें',
        'or_use_email_and_password' => 'या अपना ईमेल और पासवर्ड उपयोग करें',
        'user' => 'उपयोगकर्ता',
        'admin' => 'एडमिन',
        'welcome_back' => 'वापसी पर स्वागत है',
        'enter_personal_details' => 'सभी साइट सुविधाओं का उपयोग करने के लिए अपना व्यक्तिगत विवरण दर्ज करें',
        'hello_friend' => 'नमस्ते, दोस्त',
        'register_personal_details' => 'सभी साइट सुविधाओं का उपयोग करने के लिए अपने व्यक्तिगत विवरण के साथ पंजीकरण करें',
        
        // Additional Profile Page
        'manage_account_settings' => 'अपनी खाता सेटिंग्स प्रबंधित करें और AgriGrow से लॉगआउट करें',
        'switch_to' => 'बदलें',
        'my_applications' => 'मेरे आवेदन',
        'back_to_home' => 'होम पर वापस जाएं',
        'logout_warning' => 'लॉगआउट करने से आपका वर्तमान सेशन समाप्त हो जाएगा। अपने प्रोफाइल तक पहुंचने के लिए आपको फिर से लॉगिन करना होगा।',
        
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
    
    // Return JSON response for AJAX requests
    if (isset($_POST['ajax'])) {
        header('Content-Type: application/json');
        echo json_encode(['language' => $new_lang]);
        exit();
    }
    
    // Redirect back to the page that made the request
    $redirect_url = $_POST['redirect_url'] ?? 'homePage.php';
    header("Location: $redirect_url");
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

// Get current language display name
function getCurrentLanguageDisplayName() {
    return getLanguageDisplayName(getCurrentLanguage());
}
?> 