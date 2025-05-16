<?php
$phpIniPath = php_ini_loaded_file();
echo "PHP ini file location: " . $phpIniPath . "\n";

if (!file_exists($phpIniPath)) {
    echo "Error: Cannot find php.ini file.\n";
    exit(1);
}

$phpIniContent = file_get_contents($phpIniPath);
if ($phpIniContent === false) {
    echo "Error: Cannot read php.ini file.\n";
    exit(1);
}

// Check if zip extension is already enabled
if (strpos($phpIniContent, 'extension=zip') !== false && strpos($phpIniContent, ';extension=zip') === false) {
    echo "Zip extension is already enabled in php.ini.\n";
} else {
    // Replace ;extension=zip with extension=zip
    $phpIniContent = str_replace(';extension=zip', 'extension=zip', $phpIniContent);
    
    // If the line doesn't exist at all, add it
    if (strpos($phpIniContent, 'extension=zip') === false) {
        // Find the extensions section
        $pos = strpos($phpIniContent, '[extensions]');
        if ($pos !== false) {
            // Insert after [extensions]
            $phpIniContent = substr_replace($phpIniContent, "\nextension=zip", $pos + 12, 0);
        } else {
            // If [extensions] section doesn't exist, add it near other extensions
            $pos = strpos($phpIniContent, 'extension=');
            if ($pos !== false) {
                // Find the line end
                $lineEnd = strpos($phpIniContent, "\n", $pos);
                if ($lineEnd !== false) {
                    // Insert after the line
                    $phpIniContent = substr_replace($phpIniContent, "\nextension=zip", $lineEnd, 0);
                }
            }
        }
    }
    
    // Write the modified content back to php.ini
    $result = file_put_contents($phpIniPath, $phpIniContent);
    if ($result === false) {
        echo "Error: Cannot write to php.ini file. You may need administrator privileges.\n";
        exit(1);
    }
    
    echo "Zip extension has been enabled in php.ini.\n";
}

echo "Please restart your web server for the changes to take effect.\n";
?>
