# Hostinger Laravel Deployment Fix Guide

## 🚨 Common Issues & Solutions

### 1. **File Structure Issue**
**Problem**: Laravel files in wrong directory
**Solution**: 
- Upload Laravel files to `public_html` directory
- Move contents of `public` folder to `public_html`
- Move Laravel app files to a folder above `public_html` (like `laravel_app`)

### 2. **Environment Configuration**
**Problem**: `.env` file not configured for production
**Solution**: Create proper `.env` file with Hostinger database credentials

### 3. **Database Connection Error**
**Problem**: Database credentials incorrect
**Solution**: Update database settings in `.env`

### 4. **Permissions Error**
**Problem**: File permissions not set correctly
**Solution**: Set proper permissions via File Manager

### 5. **Composer Dependencies**
**Problem**: Vendor folder missing or outdated
**Solution**: Run composer install on server

## 🔧 Step-by-Step Fix

### Step 1: Correct File Structure
```
public_html/
├── index.php (from Laravel's public folder)
├── .htaccess (from Laravel's public folder)
├── css/
├── js/
└── other public assets

laravel_app/ (above public_html)
├── app/
├── bootstrap/
├── config/
├── database/
├── resources/
├── routes/
├── storage/
├── vendor/
├── .env
└── composer.json
```

### Step 2: Update index.php
Edit `public_html/index.php`:
```php
require __DIR__.'/../laravel_app/vendor/autoload.php';
$app = require_once __DIR__.'/../laravel_app/bootstrap/app.php';
```

### Step 3: Create Production .env
```env
APP_NAME="Zoffness College Prep"
APP_ENV=production
APP_KEY=your_app_key_here
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_hostinger_db_name
DB_USERNAME=your_hostinger_db_user
DB_PASSWORD=your_hostinger_db_password

# Set other configurations...
```

### Step 4: Set File Permissions
- Folders: 755
- Files: 644
- storage/ and bootstrap/cache/: 775

### Step 5: Clear Caches
Run these commands via SSH or create a script:
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## 🛠️ Quick Fix Script
Create `fix_deployment.php` in your root directory and run it once.
