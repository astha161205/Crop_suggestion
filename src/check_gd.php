<?php
// Check if GD library is available
echo "<h2>GD Library Check</h2>";

if (extension_loaded('gd')) {
    echo "<p style='color: green;'>✅ GD library is installed and enabled!</p>";
    echo "<p>GD Version: " . gd_info()['GD Version'] . "</p>";
    echo "<p>Supported formats:</p>";
    $gd_info = gd_info();
    echo "<ul>";
    if ($gd_info['JPEG Support']) echo "<li>✅ JPEG Support</li>";
    if ($gd_info['PNG Support']) echo "<li>✅ PNG Support</li>";
    if ($gd_info['GIF Read Support']) echo "<li>✅ GIF Read Support</li>";
    if ($gd_info['GIF Create Support']) echo "<li>✅ GIF Create Support</li>";
    echo "</ul>";
} else {
    echo "<p style='color: red;'>❌ GD library is NOT installed or enabled!</p>";
    echo "<h3>How to Enable GD Library in XAMPP:</h3>";
    echo "<ol>";
    echo "<li>Open your XAMPP Control Panel</li>";
    echo "<li>Click on 'Config' button for Apache</li>";
    echo "<li>Select 'php.ini'</li>";
    echo "<li>Find the line: <code>;extension=gd</code></li>";
    echo "<li>Remove the semicolon to make it: <code>extension=gd</code></li>";
    echo "<li>Save the file</li>";
    echo "<li>Restart Apache in XAMPP Control Panel</li>";
    echo "</ol>";
    echo "<p><strong>Alternative:</strong> If you can't enable GD, the system will use a fallback method without compression.</p>";
}
?>

