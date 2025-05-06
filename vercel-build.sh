#!/bin/bash

# Install Composer dependencies
composer install --no-dev --optimize-autoloader

# Generate application key if not set
php artisan key:generate --force

# Clear and cache config
php artisan config:clear
php artisan config:cache

# Clear and cache routes
php artisan route:clear
php artisan route:cache

# Clear and cache views
php artisan view:clear
php artisan view:cache

# Create storage symlink
php artisan storage:link

echo "Build completed successfully!"
