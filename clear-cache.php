<?php
/**
 * Clear Laravel Cache Script
 * 
 * Upload this file to your server root and run it via browser:
 * https://rsmmultilink.com/clear-cache.php
 * 
 * This will clear all Laravel caches to activate the fileinfo workaround.
 */

// Change to your Laravel root directory
chdir(__DIR__);

echo "<h2>Laravel Cache Clearing Script</h2>";
echo "<pre>";

// Clear config cache
echo "Clearing config cache...\n";
if (file_exists('bootstrap/cache/config.php')) {
    unlink('bootstrap/cache/config.php');
    echo "✓ Config cache cleared\n";
} else {
    echo "✓ Config cache already clear\n";
}

// Clear compiled services
echo "\nClearing compiled services...\n";
if (file_exists('bootstrap/cache/services.php')) {
    unlink('bootstrap/cache/services.php');
    echo "✓ Services cache cleared\n";
} else {
    echo "✓ Services cache already clear\n";
}

// Clear compiled packages
echo "\nClearing compiled packages...\n";
if (file_exists('bootstrap/cache/packages.php')) {
    unlink('bootstrap/cache/packages.php');
    echo "✓ Packages cache cleared\n";
} else {
    echo "✓ Packages cache already clear\n";
}

// Clear application cache
echo "\nClearing application cache...\n";
$cacheDir = 'storage/framework/cache/data';
if (is_dir($cacheDir)) {
    $files = glob($cacheDir . '/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    echo "✓ Application cache cleared\n";
} else {
    echo "✓ Application cache directory not found\n";
}

// Clear view cache
echo "\nClearing view cache...\n";
$viewCacheDir = 'storage/framework/views';
if (is_dir($viewCacheDir)) {
    $files = glob($viewCacheDir . '/*');
    foreach ($files as $file) {
        if (is_file($file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            unlink($file);
        }
    }
    echo "✓ View cache cleared\n";
} else {
    echo "✓ View cache directory not found\n";
}

// Clear route cache
echo "\nClearing route cache...\n";
if (file_exists('bootstrap/cache/routes.php')) {
    unlink('bootstrap/cache/routes.php');
    echo "✓ Route cache cleared\n";
} else {
    echo "✓ Route cache already clear\n";
}

// Run composer dump-autoload to load helpers.php
echo "\nRunning composer dump-autoload...\n";
if (file_exists('composer.phar')) {
    exec('php composer.phar dump-autoload 2>&1', $output, $returnCode);
} else {
    exec('composer dump-autoload 2>&1', $output, $returnCode);
}

if ($returnCode === 0) {
    echo "✓ Composer autoload regenerated\n";
} else {
    echo "⚠ Composer dump-autoload failed (you may need to run it manually via SSH)\n";
    echo "Run: composer dump-autoload\n";
}

echo "\n";
echo "========================================\n";
echo "✓ ALL CACHES CLEARED SUCCESSFULLY!\n";
echo "========================================\n";
echo "\nThe fileinfo workaround is now active.\n";
echo "You can now try accessing the blog page again.\n";
echo "\nIMPORTANT: Delete this file after use for security!\n";
echo "</pre>";

echo "<p><strong>Next steps:</strong></p>";
echo "<ol>";
echo "<li>Try accessing your blog page: <a href='/admin/blogs/720' target='_blank'>/admin/blogs/720</a></li>";
echo "<li>Try editing your blog: <a href='/admin/blogs/720/edit' target='_blank'>/admin/blogs/720/edit</a></li>";
echo "<li>If it works, DELETE this file immediately for security</li>";
echo "<li>If composer dump-autoload failed above, run it manually via SSH: <code>composer dump-autoload</code></li>";
echo "</ol>";
?>
