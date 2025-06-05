<?php
/**
 * Hostinger Laravel Deployment Fix Script
 * Upload this file to your Hostinger public_html directory and run it once
 */

echo "<h1>🔧 Hostinger Laravel Deployment Fix</h1>";
echo "<pre>";

// Check if this is running on Hostinger
if (!isset($_SERVER['HTTP_HOST'])) {
    die("This script should be run via web browser on your Hostinger hosting.");
}

echo "🌐 Domain: " . $_SERVER['HTTP_HOST'] . "\n";
echo "📁 Current Directory: " . __DIR__ . "\n\n";

// 1. Check Laravel structure
echo "1. Checking Laravel Structure...\n";
$laravel_paths = [
    '../app',
    '../bootstrap', 
    '../config',
    '../vendor',
    '../.env'
];

$missing_paths = [];
foreach ($laravel_paths as $path) {
    if (file_exists($path)) {
        echo "   ✅ Found: $path\n";
    } else {
        echo "   ❌ Missing: $path\n";
        $missing_paths[] = $path;
    }
}

// 2. Check .env file
echo "\n2. Checking Environment Configuration...\n";
if (file_exists('../.env')) {
    $env_content = file_get_contents('../.env');
    
    // Check critical settings
    $checks = [
        'APP_KEY=' => 'Application Key',
        'DB_DATABASE=' => 'Database Name',
        'DB_USERNAME=' => 'Database Username',
        'DB_PASSWORD=' => 'Database Password'
    ];
    
    foreach ($checks as $key => $name) {
        if (strpos($env_content, $key) !== false) {
            echo "   ✅ $name configured\n";
        } else {
            echo "   ❌ $name missing\n";
        }
    }
} else {
    echo "   ❌ .env file not found\n";
}

// 3. Check file permissions
echo "\n3. Checking File Permissions...\n";
$permission_checks = [
    '../storage' => 'Storage directory',
    '../bootstrap/cache' => 'Bootstrap cache',
    '.' => 'Public directory'
];

foreach ($permission_checks as $path => $name) {
    if (file_exists($path)) {
        $perms = substr(sprintf('%o', fileperms($path)), -3);
        echo "   📁 $name: $perms\n";
    }
}

// 4. Test database connection
echo "\n4. Testing Database Connection...\n";
if (file_exists('../.env')) {
    $env_lines = file('../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $env_vars = [];
    
    foreach ($env_lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $env_vars[trim($key)] = trim($value, '"\'');
        }
    }
    
    if (isset($env_vars['DB_HOST'], $env_vars['DB_DATABASE'], $env_vars['DB_USERNAME'])) {
        try {
            $pdo = new PDO(
                "mysql:host={$env_vars['DB_HOST']};dbname={$env_vars['DB_DATABASE']}", 
                $env_vars['DB_USERNAME'], 
                $env_vars['DB_PASSWORD'] ?? ''
            );
            echo "   ✅ Database connection successful\n";
        } catch (PDOException $e) {
            echo "   ❌ Database connection failed: " . $e->getMessage() . "\n";
        }
    } else {
        echo "   ❌ Database credentials not found in .env\n";
    }
}

// 5. Generate fixes
echo "\n5. 🛠️ Recommended Fixes:\n";

if (!empty($missing_paths)) {
    echo "\n   📋 Missing Laravel Files:\n";
    echo "   - Upload your complete Laravel project to a folder above public_html\n";
    echo "   - Move only the contents of Laravel's 'public' folder to public_html\n";
}

echo "\n   📝 Create/Update .env file with these settings:\n";
echo "   APP_ENV=production\n";
echo "   APP_DEBUG=false\n";
echo "   APP_URL=https://{$_SERVER['HTTP_HOST']}\n";
echo "   DB_HOST=localhost\n";
echo "   DB_DATABASE=your_hostinger_database_name\n";
echo "   DB_USERNAME=your_hostinger_database_user\n";
echo "   DB_PASSWORD=your_hostinger_database_password\n";

echo "\n   🔧 Update index.php in public_html:\n";
echo "   Change paths to point to your Laravel app folder above public_html\n";

echo "\n   🗂️ Set File Permissions:\n";
echo "   - Folders: 755\n";
echo "   - Files: 644\n";
echo "   - storage/ and bootstrap/cache/: 775\n";

echo "\n✅ Fix script completed!\n";
echo "📞 If you need help, check the HOSTINGER_DEPLOYMENT_FIX.md guide\n";

echo "</pre>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
pre { background: #2d3748; color: #e2e8f0; padding: 20px; border-radius: 8px; overflow-x: auto; }
h1 { color: #2d3748; }
</style>
