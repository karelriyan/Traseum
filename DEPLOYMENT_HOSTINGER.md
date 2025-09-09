# 🚀 Deployment Guide - Laravel React App ke Hostinger

Panduan lengkap untuk deploy aplikasi Laravel + React (Inertia.js) ke Hostinger menggunakan SSH.

## 📋 Prerequisites

- ✅ Akun Hostinger dengan SSH access
- ✅ Domain sudah pointing ke hosting
- ✅ Git repository siap
- ✅ Database MySQL di Hostinger

## 🔧 Step 1: Koneksi SSH ke Server

```bash
# Koneksi ke server Hostinger
ssh u376598477@ciptamuri.com

# Atau menggunakan IP
ssh u376598477@ip-server-hostinger
```

## 📁 Step 2: Setup Directory Structure

```bash
# Masuk ke home directory
cd ~

# Clone repository (ganti dengan repo Anda)
git clone https://github.com/username/cipta-muri.git
cd cipta-muri

# Struktur yang akan terbentuk:
# /home/u376598477/
# ├── cipta-muri/          # Project Laravel
# ├── domains/
# │   └── ciptamuri.com/
# │       └── public_html/ # Document root
```

## 🔨 Step 3: Install Dependencies Backend

```bash
# Install Composer dependencies
composer install --no-dev --optimize-autoloader

# Setup environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

## ⚙️ Step 4: Konfigurasi Environment

```bash
# Edit environment file
nano .env
```

**Update konfigurasi berikut:**

```env
APP_NAME="Bank Sampah Cipta Muri"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://ciptamuri.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u376598477_cipta_muri
DB_USERNAME=u376598477_dbuser
DB_PASSWORD=your_database_password

# Session & Cache
SESSION_DRIVER=file
CACHE_DRIVER=file

# Mail Configuration (opsional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=your-email@ciptamuri.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
```

## 🗄️ Step 5: Setup Database

```bash
# Jalankan migrasi
php artisan migrate --force

# Jalankan seeder
php artisan db:seed --force

# Atau migrasi fresh (hati-hati, ini akan hapus data!)
# php artisan migrate:fresh --seed --force
```

## 📦 Step 6: Install Node.js & Build Frontend

### Install NVM dan Node.js:

```bash
# Install NVM (jika belum ada)
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash

# Load NVM
export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"

# Install Node.js LTS
nvm install --lts
nvm use --lts
nvm alias default node

# Verifikasi
node --version
npm --version
```

### Build Assets:

```bash
# Install dependencies
npm ci --production

# Build untuk production
npm run build

# Jika build gagal di server, gunakan alternative:
# 1. Build di local computer
# 2. Upload folder public/build via FTP/SCP
```

## 🔗 Step 7: Setup Web Directory

### Option A: Symlink (Recommended)

```bash
# Backup public_html existing (jika ada)
mv ~/domains/ciptamuri.com/public_html ~/domains/ciptamuri.com/public_html_backup

# Hapus symlink lama jika ada dan rusak
rm -f ~/domains/ciptamuri.com/public_html

# Buat symlink baru dari Laravel public ke public_html
ln -sf ~/cipta-muri/public ~/domains/ciptamuri.com/public_html

# Verify symlink berhasil
ls -la ~/domains/ciptamuri.com/
ls -la ~/domains/ciptamuri.com/public_html/

# Test akses directory
cd ~/domains/ciptamuri.com/public_html && pwd

# TESTING SYMLINK - Cara lengkap test symlink:

# 1. Check apakah symlink valid
file ~/domains/ciptamuri.com/public_html

# 2. Check target symlink
readlink ~/domains/ciptamuri.com/public_html

# 3. Test apakah bisa akses file di target
ls ~/domains/ciptamuri.com/public_html/index.php

# 4. Compare dengan original
ls ~/cipta-muri/public/index.php

# 5. Test write permission
touch ~/domains/ciptamuri.com/public_html/test_symlink.txt
ls ~/cipta-muri/public/test_symlink.txt  # Harus ada di kedua lokasi

# 6. Clean up test file
rm ~/domains/ciptamuri.com/public_html/test_symlink.txt
```

### Option B: Copy Files (Alternative jika symlink bermasalah)

```bash
# Buat directory public_html baru
mkdir -p ~/domains/ciptamuri.com/public_html

# Copy semua file dari Laravel public
cp -r ~/cipta-muri/public/* ~/domains/ciptamuri.com/public_html/

# Update index.php
nano ~/domains/ciptamuri.com/public_html/index.php
```

**Update paths di index.php:**

```php
<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Update path ke project Laravel
require __DIR__.'/../../cipta-muri/vendor/autoload.php';

$app = require_once __DIR__.'/../../cipta-muri/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
```

## 🔐 Step 8: Setup Storage & Permissions

```bash
# Buat storage link
cd ~/cipta-muri
php artisan storage:link

# Set permissions (jika diperlukan)
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

# Link storage ke public_html (jika menggunakan copy method)
ln -sf ~/domains/cipta-muri/storage/app/public ~/domains/ciptamuri.com/public_html/storage

# Test storage link
echo "test" > storage/app/public/test.txt
curl https://ciptamuri.com/storage/test.txt
```

## ⚡ Step 9: Optimasi Production

```bash
cd ~/cipta-muri

# Cache konfigurasi
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Clear development cache
php artisan cache:clear
```

## 🌐 Step 10: Setup .htaccess

Pastikan file `.htaccess` ada di public_html:

```bash
nano ~/domains/ciptamuri.com/public_html/.htaccess
```

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

## 🔍 Step 11: Testing & Verification

```bash
# Test aplikasi
curl -I https://ciptamuri.com

# Check logs jika ada error
tail -f ~/cipta-muri/storage/logs/laravel.log

# Test admin panel
# https://ciptamuri.com/admin
```

## 🔄 Step 12: Setup Auto Deploy Script

```bash
# Buat script deploy
nano ~/deploy.sh
```

```bash
#!/bin/bash

echo "🚀 Starting deployment..."

# Navigate to project
cd ~/cipta-muri

# Pull latest code
git pull origin main

# Update dependencies
composer install --no-dev --optimize-autoloader

# Build assets (jika Node.js tersedia di server)
npm ci --production
npm run build

# Update database
php artisan migrate --force

# Clear & cache
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Copy build jika menggunakan copy method
# cp -r public/build ~/domains/ciptamuri.com/public_html/

echo "✅ Deployment completed!"
```

```bash
# Make executable
chmod +x ~/deploy.sh

# Usage untuk update future
./deploy.sh
```

## 🐛 Troubleshooting

### Troubleshooting Broken Symlink:

````bash
```bash
# QUICK FIX untuk symlink rusak:

# 1. Check source project exists
if [ ! -d ~/cipta-muri ]; then
    echo "❌ Project cipta-muri tidak ditemukan"
    echo "Clone project terlebih dahulu:"
    echo "git clone https://github.com/your-username/cipta-muri.git ~/cipta-muri"
    exit 1
fi

# 2. Check public directory exists
if [ ! -d ~/cipta-muri/public ]; then
    echo "❌ Folder public tidak ditemukan - bukan project Laravel"
    exit 1
fi

# 3. Check index.php exists
if [ ! -f ~/cipta-muri/public/index.php ]; then
    echo "❌ File index.php tidak ditemukan"
    exit 1
fi

# 4. Proceed with copy method
rm -f ~/domains/ciptamuri.com/public_html
mkdir -p ~/domains/ciptamuri.com/public_html
cp -r ~/cipta-muri/public/* ~/domains/ciptamuri.com/public_html/

# 5. Update index.php dengan path yang benar
sed -i 's|__DIR__.*vendor|__DIR__."/../../cipta-muri/vendor|g' ~/domains/ciptamuri.com/public_html/index.php
sed -i 's|__DIR__.*bootstrap|__DIR__."/../../cipta-muri/bootstrap|g' ~/domains/ciptamuri.com/public_html/index.php

# 6. Test setup
curl -I https://ciptamuri.com
````

````

### Testing & Fixing Symlink Issues

```bash
# 1. Diagnosa symlink status
ls -la ~/domains/ciptamuri.com/public_html

# Output yang diharapkan:
# lrwxrwxrwx ... public_html -> /home/u376598477/cipta-muri/public

# 2. Test validitas symlink
file ~/domains/ciptamuri.com/public_html

# Output jika berhasil: "symbolic link to /home/u376598477/cipta-muri/public"
# Output jika rusak: "broken symbolic link to ..."

# 3. Check target path exists
ls -la ~/cipta-muri/public/

# 4. Recreate symlink jika rusak
rm -f ~/domains/ciptamuri.com/public_html
ln -sf ~/cipta-muri/public ~/domains/ciptamuri.com/public_html

# 5. Test akses file melalui symlink
cat ~/domains/ciptamuri.com/public_html/index.php | head -5

# 6. Test web access
curl -I http://ciptamuri.com
````

### Permission Issues dengan Symlink:

```bash
# Check permissions directory target
ls -la ~/cipta-muri/public/
chmod 755 ~/cipta-muri/public/

# Check permissions file index.php
ls -la ~/cipta-muri/public/index.php
chmod 644 ~/cipta-muri/public/index.php
```

### Alternative Jika Symlink Tidak Didukung:

```bash
# Method 1: Hard link (jika filesystem sama)
ln ~/cipta-muri/public/index.php ~/domains/ciptamuri.com/public_html/index.php

# Method 2: Copy dengan sync
rsync -av ~/cipta-muri/public/ ~/domains/ciptamuri.com/public_html/

# Method 3: Bind mount (perlu root access)
# sudo mount --bind ~/cipta-muri/public ~/domains/ciptamuri.com/public_html
```

### Build Error di Server:

```bash
# Alternative: Build di local, upload via SCP
# Di local computer:
npm run build
scp -r public/build user@server:~/cipta-muri/public/
```

### Permission Issues:

```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### Database Connection Error:

- Check kredensial database di `.env`
- Pastikan database sudah dibuat di cPanel
- Test koneksi: `php artisan migrate:status`

### 500 Internal Server Error:

```bash
# Check error logs
tail -f storage/logs/laravel.log

# Check web server logs
tail -f ~/logs/error.log
```

## 📋 Checklist Final

- [ ] ✅ SSH connection berhasil
- [ ] ✅ Repository ter-clone
- [ ] ✅ Dependencies terinstall
- [ ] ✅ Environment file dikonfigurasi
- [ ] ✅ Database setup selesai
- [ ] ✅ Assets ter-build
- [ ] ✅ Web directory setup
- [ ] ✅ Storage permissions benar
- [ ] ✅ Production cache aktif
- [ ] ✅ Website dapat diakses
- [ ] ✅ Admin panel berfungsi

## 🎉 Selesai!

Website seharusnya sudah bisa diakses di:

- **Frontend**: https://ciptamuri.com
- **Admin Panel**: https://ciptamuri.com/admin

---

**📧 Support:** Jika ada masalah, check logs dan pastikan semua langkah sudah diikuti dengan benar.
