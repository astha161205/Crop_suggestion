# Multilingual Support Guide for AgriGrow

This guide explains how to implement Hindi and English language support across your entire crop suggestion website.

## 🚀 Quick Start

### 1. Files Already Created
- `language_manager.php` - Core language management system
- `language.js` - JavaScript for smooth language switching
- `login_multilingual.php` - Example of a multilingual page

### 2. How to Use

#### Step 1: Include Language Manager
Add this line at the top of any PHP file where you want language support:
```php
require_once 'language_manager.php';
```

#### Step 2: Use Translation Function
Replace hardcoded text with translation function:
```php
// Instead of:
echo "Welcome";

// Use:
echo __('welcome');
```

#### Step 3: Add Language Toggle
Add this HTML to your navigation:
```php
<form method="POST" class="inline" data-language-toggle>
    <input type="hidden" name="toggle_language" value="1">
    <input type="hidden" name="redirect_url" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <button type="submit" class="px-3 py-1 rounded border text-sm">
        <?php echo getCurrentLanguageDisplayName(); ?>
    </button>
</form>
```

#### Step 4: Include JavaScript
Add this to your HTML head:
```html
<script src="./language.js"></script>
```

## 📝 Adding New Translations

### 1. Add to English Translations
In `language_manager.php`, add to the `'en'` array:
```php
'en' => [
    // ... existing translations
    'new_key' => 'New English Text',
],
```

### 2. Add to Hindi Translations
In the same file, add to the `'hi'` array:
```php
'hi' => [
    // ... existing translations
    'new_key' => 'नया हिंदी टेक्स्ट',
],
```

### 3. Use in Your Code
```php
echo __('new_key');
```

## 🔧 Converting Existing Pages

### Example: Converting a Simple Page

**Before:**
```php
<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Page</title>
</head>
<body>
    <h1>Welcome to our website</h1>
    <p>This is some content in English.</p>
    <a href="home.php">Go Home</a>
</body>
</html>
```

**After:**
```php
<?php
session_start();
require_once 'language_manager.php';
?>
<!DOCTYPE html>
<html lang="<?php echo getCurrentLanguage(); ?>">
<head>
    <title><?php echo __('page_title'); ?> - AgriGrow</title>
    <script src="./language.js"></script>
</head>
<body>
    <!-- Language Toggle -->
    <form method="POST" class="inline" data-language-toggle>
        <input type="hidden" name="toggle_language" value="1">
        <input type="hidden" name="redirect_url" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
        <button type="submit" class="px-3 py-1 rounded border text-sm">
            <?php echo getCurrentLanguageDisplayName(); ?>
        </button>
    </form>
    
    <h1><?php echo __('welcome_message'); ?></h1>
    <p><?php echo __('page_content'); ?></p>
    <a href="home.php"><?php echo __('home'); ?></a>
</body>
</html>
```

## 📋 Common Translation Keys

### Navigation
- `home` - Home
- `subsidies` - Subsidies
- `blog` - Blog
- `about_us` - About us
- `login` - Login
- `logout` - Logout
- `profile` - Profile

### Forms
- `email` - Email
- `password` - Password
- `name` - Name
- `submit` - Submit
- `register` - Register

### Messages
- `login_success` - Login successful!
- `login_failed` - Login failed
- `registration_success` - Registration successful!
- `fill_all_fields` - Please fill all fields

### Services
- `crop_recommendation` - Crop Recommendation
- `weather_forecast` - Weather Forecast
- `pest_management` - Pest Management
- `farm_technology` - Farm Technology

## 🌐 Language Features

### Current Languages
- **English (en)** - Default language
- **Hindi (hi)** - हिंदी

### Language Switching
- Users can switch languages using the toggle button
- Language preference is saved in session
- Page reloads to show new language
- Smooth AJAX switching (with fallback)

### Fallback System
- If a translation is missing in the current language, it falls back to English
- If no translation exists, it shows the key name

## 🔒 Security Features

### SQL Injection Protection
- All database queries use prepared statements
- User input is properly sanitized
- Password hashing for security

### XSS Protection
- All output is properly escaped using `htmlspecialchars()`
- No raw HTML output from user data

## 📱 Responsive Design

### Mobile-Friendly
- Language toggle button is responsive
- Text scales properly on mobile devices
- Touch-friendly interface

### Accessibility
- Proper HTML lang attributes
- Screen reader friendly
- Keyboard navigation support

## 🚀 Performance Tips

### Optimization
- Language files are loaded once per session
- Minimal JavaScript overhead
- Efficient translation lookup

### Caching
- Session-based language storage
- No database queries for language switching
- Fast page loads

## 🔧 Troubleshooting

### Common Issues

1. **Language not switching**
   - Check if `language_manager.php` is included
   - Verify form has `data-language-toggle` attribute
   - Check browser console for JavaScript errors

2. **Missing translations**
   - Add missing keys to both language arrays
   - Check for typos in translation keys
   - Use `__('key_name')` function correctly

3. **Database connection errors**
   - Ensure XAMPP MySQL is running
   - Check database credentials
   - Verify database exists

### Debug Mode
Add this to see current language:
```php
echo "Current language: " . getCurrentLanguage();
echo "Display name: " . getCurrentLanguageDisplayName();
```

## 📚 Best Practices

### 1. Consistent Naming
- Use descriptive, lowercase keys
- Separate words with underscores
- Group related translations together

### 2. Context Matters
- Provide context in translation keys
- Use specific keys for different contexts
- Avoid generic keys like "title"

### 3. Testing
- Test both languages thoroughly
- Check text length differences
- Verify mobile responsiveness

### 4. Maintenance
- Keep translations up to date
- Add new translations as needed
- Document any new keys added

## 🎯 Next Steps

### Immediate Actions
1. ✅ Language manager is ready
2. ✅ Homepage has basic language support
3. ✅ Login page example created
4. 🔄 Convert remaining pages one by one

### Future Enhancements
- Add more languages (Gujarati, Marathi, etc.)
- Database storage for user language preferences
- Automatic language detection
- Translation management interface

### Priority Pages to Convert
1. Crop recommendation page
2. Weather page
3. Pest management page
4. Subsidies page
5. Blog page
6. Profile page
7. Admin panel

## 📞 Support

If you need help implementing multilingual support:
1. Check this guide first
2. Look at the example files
3. Test with the provided setup
4. Add translations gradually

The system is designed to be easy to implement and maintain! 