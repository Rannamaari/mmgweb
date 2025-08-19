#!/bin/bash

# MMG Website Deployment Script for DigitalOcean
echo "🚀 Starting MMG Website Deployment..."

# Update system packages
echo "📦 Updating system packages..."
sudo apt update && sudo apt upgrade -y

# Install required packages
echo "🔧 Installing required packages..."
sudo apt install -y nginx php8.2-fpm php8.2-pgsql php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath php8.2-intl composer git unzip

# Create application directory
echo "📁 Setting up application directory..."
sudo mkdir -p /var/www/mmgweb
sudo chown -R $USER:$USER /var/www/mmgweb

# Clone the repository
echo "📥 Cloning repository..."
cd /var/www/mmgweb
git clone https://github.com/Rannamaari/mmgweb.git .

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

# Install Node.js dependencies and build assets
echo "📦 Installing Node.js dependencies..."
npm install
npm run build

# Set up environment file
echo "⚙️ Setting up environment configuration..."
cat > .env << 'EOF'
APP_NAME="Micro Moto Garage"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://your-domain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=pgsql
DB_HOST=micronetdb-do-user-24249606-0.d.db.ondigitalocean.com
DB_PORT=25060
DB_DATABASE=mmgweb
DB_USERNAME=doadmin
DB_PASSWORD=AVNS_L5g7hboPAo5bkQEvWbu
DB_SSLMODE=require

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME="${APP_NAME}"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
EOF

# Generate application key
echo "🔑 Generating application key..."
php artisan key:generate

# Set proper permissions
echo "🔐 Setting proper permissions..."
sudo chown -R www-data:www-data /var/www/mmgweb
sudo chmod -R 755 /var/www/mmgweb
sudo chmod -R 775 /var/www/mmgweb/storage
sudo chmod -R 775 /var/www/mmgweb/bootstrap/cache

# Run database migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Seed the database
echo "🌱 Seeding the database..."
php artisan db:seed --force

# Clear and cache configuration
echo "🧹 Clearing and caching configuration..."
php artisan config:clear
php artisan config:cache
php artisan route:clear
php artisan route:cache
php artisan view:clear
php artisan view:cache

# Set up Nginx configuration
echo "🌐 Setting up Nginx configuration..."
sudo tee /etc/nginx/sites-available/mmgweb << 'EOF'
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
EOF

# Enable the site
echo "✅ Enabling Nginx site..."
sudo ln -sf /etc/nginx/sites-available/mmgweb /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default

# Test Nginx configuration
echo "🧪 Testing Nginx configuration..."
sudo nginx -t

# Restart services
echo "🔄 Restarting services..."
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx

# Set up SSL with Let's Encrypt (optional)
echo "🔒 Setting up SSL (optional)..."
echo "To enable SSL, run: sudo apt install certbot python3-certbot-nginx"
echo "Then: sudo certbot --nginx -d your-domain.com"

echo "🎉 Deployment completed!"
echo "🌐 Your MMG website should now be accessible at: http://your-domain.com"
echo "🔧 Admin panel: http://your-domain.com/admin"
echo "📱 POS system: http://your-domain.com/pos"
echo "📅 Booking system: http://your-domain.com/booking"
