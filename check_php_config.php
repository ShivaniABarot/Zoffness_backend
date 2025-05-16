<?php
echo "PHP Version: " . PHP_VERSION . "\n";
echo "PHP ini file location: " . php_ini_loaded_file() . "\n";
echo "Zip extension loaded: " . (extension_loaded('zip') ? 'Yes' : 'No') . "\n";
echo "Extensions directory: " . ini_get('extension_dir') . "\n";
echo "Available extensions:\n";
print_r(get_loaded_extensions());
?>
