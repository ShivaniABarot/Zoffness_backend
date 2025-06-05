<?php
/**
 * EMERGENCY HOSTINGER SESSION FIX
 * Upload this to your public_html and run it ONCE
 * URL: https://zoffness.academy/emergency_hostinger_fix.php
 */

echo "<h1>🚨 Emergency Hostinger Session Fix</h1>";
echo "<pre>";

// 1. Check current directory structure
echo "1. Checking Directory Structure...\n";
echo "Current directory: " . __DIR__ . "\n";
echo "Server path: " . $_SERVER['DOCUMENT_ROOT'] . "\n";

// 2. Check if storage directories exist
$storage_paths = [
    '../storage',
    '../storage/framework',
    '../storage/framework/sessions',
    '../storage/framework/cache',
    '../storage/framework/views',
    '../storage/logs'
];

echo "\n2. Checking Storage Directories...\n";
foreach ($storage_paths as $path) {
    if (is_dir($path)) {
        echo "✅ $path exists\n";
        // Check permissions
        $perms = substr(sprintf('%o', fileperms($path)), -3);
        echo "   Permissions: $perms\n";
    } else {
        echo "❌ $path missing - Creating...\n";
        if (mkdir($path, 0755, true)) {
            echo "✅ Created $path\n";
        } else {
            echo "❌ Failed to create $path\n";
        }
    }
}

// 3. Create sessions table SQL
echo "\n3. Creating Sessions Table SQL...\n";
$sessions_sql = "
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

// Try to connect to database and create sessions table
if (file_exists('../.env')) {
    echo "Reading .env file...\n";
    $env_content = file_get_contents('../.env');
    $env_lines = explode("\n", $env_content);
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
            
            echo "✅ Database connected\n";
            
            // Create sessions table
            $pdo->exec($sessions_sql);
            echo "✅ Sessions table created/verified\n";
            
        } catch (PDOException $e) {
            echo "❌ Database error: " . $e->getMessage() . "\n";
        }
    }
}

// 4. Create updated .env content
echo "\n4. Creating Updated .env Configuration...\n";
$new_env_content = "APP_NAME=\"Zoffness College Prep\"
APP_ENV=production
APP_KEY=base64:N+Hl1G7hjcOBiLstdojfj8ONUZNqRQODe1DJlkVeYOc
APP_DEBUG=false
APP_URL=https://zoffness.academy

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_hostinger_database_name
DB_USERNAME=your_hostinger_database_username
DB_PASSWORD=your_hostinger_database_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=database
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=\"noreply@zoffness.academy\"
MAIL_FROM_NAME=\"Zoffness College Prep\"

STRIPE_KEY=pk_test_your_stripe_publishable_key_here
STRIPE_SECRET=sk_test_your_stripe_secret_key_here";

echo "✅ New .env configuration ready\n";

echo "\n🔧 MANUAL STEPS REQUIRED:\n";
echo "1. Update your .env file with the configuration above\n";
echo "2. Replace DB_DATABASE, DB_USERNAME, DB_PASSWORD with your Hostinger values\n";
echo "3. Set SESSION_DRIVER=database in .env\n";
echo "4. Make sure storage directories have 755 permissions\n";
echo "5. Clear all caches if you have SSH access:\n";
echo "   php artisan config:clear\n";
echo "   php artisan cache:clear\n";
echo "   php artisan route:clear\n";

echo "\n✅ Emergency fix completed!\n";
echo "Delete this file after running: emergency_hostinger_fix.php\n";

echo "</pre>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
pre { background: #2d3748; color: #e2e8f0; padding: 20px; border-radius: 8px; overflow-x: auto; }
h1 { color: #dc2626; }
</style>
