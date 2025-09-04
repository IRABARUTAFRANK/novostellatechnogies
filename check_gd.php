<?php
echo "<h2>GD Extension Status Check</h2>";

// Check if GD extension is loaded
if (extension_loaded('gd')) {
    echo "<p style='color: green;'>✅ GD Extension is loaded!</p>";
    
    // Get GD information
    $gd_info = gd_info();
    echo "<h3>GD Information:</h3>";
    echo "<pre>";
    print_r($gd_info);
    echo "</pre>";
    
    // Test specific image format support
    echo "<h3>Image Format Support:</h3>";
    echo "<ul>";
    echo "<li>JPEG Support: " . (imagetypes() & IMG_JPG ? "✅ Yes" : "❌ No") . "</li>";
    echo "<li>PNG Support: " . (imagetypes() & IMG_PNG ? "✅ Yes" : "❌ No") . "</li>";
    echo "<li>GIF Support: " . (imagetypes() & IMG_GIF ? "✅ Yes" : "❌ No") . "</li>";
    echo "<li>WebP Support: " . (imagetypes() & IMG_WEBP ? "✅ Yes" : "❌ No") . "</li>";
    echo "</ul>";
    
} else {
    echo "<p style='color: red;'>❌ GD Extension is NOT loaded</p>";
}

// Also check all loaded extensions
echo "<h3>All Loaded Extensions:</h3>";
$extensions = get_loaded_extensions();
sort($extensions);
echo "<pre>";
foreach($extensions as $ext) {
    echo $ext . "\n";
}
echo "</pre>";
?>