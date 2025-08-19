# MMG Website Deployment Guide for DigitalOcean

## 🚀 Quick Deployment

### Prerequisites
- DigitalOcean Droplet (Ubuntu 22.04 LTS recommended)
- Domain name pointing to your droplet
- DigitalOcean PostgreSQL database (already configured)

### Step 1: Connect to Your Droplet
```bash
ssh root@your-droplet-ip
```

### Step 2: Run the Deployment Script
```bash
# Download the deployment script
wget https://raw.githubusercontent.com/Rannamaari/mmgweb/main/deploy.sh

# Make it executable
chmod +x deploy.sh

# Run the deployment
./deploy.sh
```

### Step 3: Update Domain Configuration
Edit the Nginx configuration:
```bash
sudo nano /etc/nginx/sites-available/mmgweb
```

Replace `your-domain.com` with your actual domain name.

### Step 4: Update Environment File
```bash
sudo nano /var/www/mmgweb/.env
```

Update the `APP_URL` with your domain:
```
APP_URL=https://your-domain.com
```

### Step 5: Restart Services
```bash
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
```

## 🔧 Manual Deployment Steps

### 1. System Setup
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install -y nginx php8.2-fpm php8.2-pgsql php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath php8.2-intl composer git unzip nodejs npm
```

### 2. Application Setup
```bash
# Create application directory
sudo mkdir -p /var/www/mmgweb
sudo chown -R $USER:$USER /var/www/mmgweb

# Clone repository
cd /var/www/mmgweb
git clone https://github.com/Rannamaari/mmgweb.git .

# Install dependencies
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

### 3. Environment Configuration
Create `.env` file with your database credentials:
```bash
cp .env.example .env
nano .env
```

Update these values:
```env
APP_NAME="Micro Moto Garage"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=pgsql
DB_HOST=micronetdb-do-user-24249606-0.d.db.ondigitalocean.com
DB_PORT=25060
DB_DATABASE=mmgweb
DB_USERNAME=doadmin
DB_PASSWORD=AVNS_L5g7hboPAo5bkQEvWbu
DB_SSLMODE=require
```

### 4. Laravel Setup
```bash
# Generate application key
php artisan key:generate

# Set permissions
sudo chown -R www-data:www-data /var/www/mmgweb
sudo chmod -R 755 /var/www/mmgweb
sudo chmod -R 775 /var/www/mmgweb/storage
sudo chmod -R 775 /var/www/mmgweb/bootstrap/cache

# Run migrations and seed
php artisan migrate --force
php artisan db:seed --force

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 5. Nginx Configuration
Create Nginx site configuration:
```bash
sudo nano /etc/nginx/sites-available/mmgweb
```

Add this configuration:
```nginx
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    root /var/www/mmgweb/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable the site:
```bash
sudo ln -sf /etc/nginx/sites-available/mmgweb /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl restart nginx
```

## 🔒 SSL Setup (Optional but Recommended)

### Install Certbot
```bash
sudo apt install certbot python3-certbot-nginx
```

### Get SSL Certificate
```bash
sudo certbot --nginx -d your-domain.com
```

## 📱 Access Your Application

After deployment, your application will be available at:

- **Main Website**: `https://your-domain.com`
- **Admin Panel**: `https://your-domain.com/admin`
- **POS System**: `https://your-domain.com/pos`
- **Booking System**: `https://your-domain.com/booking`

## 🔧 Admin Access

Default admin credentials (created by seeder):
- **Email**: admin@mmg.mv
- **Password**: password

**Important**: Change the password after first login!

## 🛠️ Maintenance Commands

### Update Application
```bash
cd /var/www/mmgweb
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo systemctl restart php8.2-fpm
```

### View Logs
```bash
# Laravel logs
tail -f /var/www/mmgweb/storage/logs/laravel.log

# Nginx logs
sudo tail -f /var/log/nginx/access.log
sudo tail -f /var/log/nginx/error.log

# PHP-FPM logs
sudo tail -f /var/log/php8.2-fpm.log
```

### Backup Database
```bash
# Create backup
pg_dump "postgresql://doadmin:AVNS_L5g7hboPAo5bkQEvWbu@micronetdb-do-user-24249606-0.d.db.ondigitalocean.com:25060/mmgweb?sslmode=require" > backup_$(date +%Y%m%d_%H%M%S).sql
```

## 🚨 Troubleshooting

### Common Issues

1. **500 Internal Server Error**
   - Check Laravel logs: `tail -f /var/www/mmgweb/storage/logs/laravel.log`
   - Verify permissions: `sudo chown -R www-data:www-data /var/www/mmgweb`

2. **Database Connection Error**
   - Verify database credentials in `.env`
   - Check if PostgreSQL is accessible from your droplet

3. **Nginx 404 Error**
   - Verify Nginx configuration: `sudo nginx -t`
   - Check if the site is enabled: `ls -la /etc/nginx/sites-enabled/`

4. **Permission Denied**
   - Set proper permissions: `sudo chmod -R 755 /var/www/mmgweb`
   - Set storage permissions: `sudo chmod -R 775 /var/www/mmgweb/storage`

### Support
If you encounter issues, check:
1. Laravel logs in `/var/www/mmgweb/storage/logs/`
2. Nginx logs in `/var/log/nginx/`
3. PHP-FPM logs in `/var/log/php8.2-fpm.log`

## 🎉 Success!

Your MMG website is now live on DigitalOcean! 🚀
