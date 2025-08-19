#!/bin/bash

# MMG Website Deployment Script for DigitalOcean
# Enhanced with security and error handling

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}📋 $1${NC}"
}

print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

# Function to check if command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Function to check if service is running
service_running() {
    systemctl is-active --quiet "$1"
}

# Function to verify database connection
verify_database() {
    print_status "Verifying database connection..."
    
    # Check if PostgreSQL client is installed
    if ! command_exists psql; then
        print_warning "PostgreSQL client not found, installing..."
        sudo apt install -y postgresql-client
    fi
    
    # Test database connection
    if psql "postgresql://doadmin:${DB_PASSWORD}@${DB_HOST}:${DB_PORT}/${DB_DATABASE}?sslmode=require" -c "SELECT 1;" >/dev/null 2>&1; then
        print_success "Database connection successful"
    else
        print_error "Database connection failed. Please check your credentials."
        exit 1
    fi
}

# Configuration variables (should be set before running)
DOMAIN_NAME="${DOMAIN_NAME:-your-domain.com}"
DB_HOST="${DB_HOST:-micronetdb-do-user-24249606-0.d.db.ondigitalocean.com}"
DB_PORT="${DB_PORT:-25060}"
DB_DATABASE="${DB_DATABASE:-mmgweb}"
DB_USERNAME="${DB_USERNAME:-doadmin}"
DB_PASSWORD="${DB_PASSWORD:-AVNS_L5g7hboPAo5bkQEvWbu}"

# Validate required variables
if [ "$DOMAIN_NAME" = "your-domain.com" ]; then
    print_error "Please set DOMAIN_NAME environment variable before running this script"
    print_warning "Example: export DOMAIN_NAME=mmg.mv"
    exit 1
fi

print_status "Starting MMG Website Deployment..."
print_status "Domain: $DOMAIN_NAME"
print_status "Database: $DB_HOST:$DB_PORT/$DB_DATABASE"

# Update system packages
print_status "Updating system packages..."
sudo apt update && sudo apt upgrade -y

# Install required packages
print_status "Installing required packages..."

# Detect PHP version
PHP_VERSION=$(php -r "echo PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;" 2>/dev/null || echo "8.3")

if [ "$PHP_VERSION" = "8.4" ]; then
    print_status "Detected PHP 8.4, installing compatible packages..."
    sudo apt install -y nginx php8.4-fpm php8.4-pgsql php8.4-mbstring php8.4-xml php8.4-curl php8.4-zip php8.4-gd php8.4-bcmath php8.4-intl php8.4-mysql php8.4-cli php8.4-common php8.4-json php8.4-opcache php8.4-readline php8.4-sqlite3 php8.4-tokenizer php8.4-xmlwriter composer git unzip
    PHP_FPM_VERSION="8.4"
elif [ "$PHP_VERSION" = "8.3" ]; then
    print_status "Detected PHP 8.3, installing compatible packages..."
    sudo apt install -y nginx php8.3-fpm php8.3-pgsql php8.3-mbstring php8.3-xml php8.3-curl php8.3-zip php8.3-gd php8.3-bcmath php8.3-intl php8.3-mysql php8.3-cli php8.3-common php8.3-json php8.3-opcache php8.3-readline php8.3-sqlite3 php8.3-tokenizer php8.3-xmlwriter composer git unzip
    PHP_FPM_VERSION="8.3"
else
    print_status "Installing PHP 8.3 packages..."
    sudo apt install -y nginx php8.3-fpm php8.3-pgsql php8.3-mbstring php8.3-xml php8.3-curl php8.3-zip php8.3-gd php8.3-bcmath php8.3-intl php8.3-mysql php8.3-cli php8.3-common php8.3-json php8.3-opcache php8.3-readline php8.3-sqlite3 php8.3-tokenizer php8.3-xmlwriter composer git unzip
    PHP_FPM_VERSION="8.3"
fi

# Install Node.js if not present
if ! command_exists node; then
    print_status "Installing Node.js..."
    curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
    sudo apt install -y nodejs
fi

# Create application directory
print_status "Setting up application directory..."
sudo mkdir -p /var/www/mmgweb
sudo chown -R $USER:$USER /var/www/mmgweb

# Clone the repository
print_status "Cloning repository..."
cd /var/www/mmgweb

# Remove existing content if present
if [ -d ".git" ]; then
    print_warning "Repository already exists, pulling latest changes..."
    git pull origin main
else
    git clone https://github.com/Rannamaari/mmgweb.git .
fi

# Install PHP dependencies
print_status "Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

# Install Node.js dependencies and build assets
print_status "Installing Node.js dependencies..."
npm install
npm run build

# Verify database connection before proceeding
verify_database

# Set up environment file
print_status "Setting up environment configuration..."
cat > .env << EOF
APP_NAME="Micro Moto Garage"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://${DOMAIN_NAME}

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=pgsql
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}
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
MAIL_FROM_ADDRESS="hello@${DOMAIN_NAME}"
MAIL_FROM_NAME="\${APP_NAME}"

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

VITE_APP_NAME="\${APP_NAME}"
VITE_PUSHER_APP_KEY="\${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="\${PUSHER_HOST}"
VITE_PUSHER_PORT="\${PUSHER_PORT}"
VITE_PUSHER_SCHEME="\${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="\${PUSHER_APP_CLUSTER}"
EOF

# Generate application key
print_status "Generating application key..."
php artisan key:generate

# Set proper permissions
print_status "Setting proper permissions..."
sudo chown -R www-data:www-data /var/www/mmgweb
sudo chmod -R 755 /var/www/mmgweb
sudo chmod -R 775 /var/www/mmgweb/storage
sudo chmod -R 775 /var/www/mmgweb/bootstrap/cache

# Run database migrations
print_status "Running database migrations..."
php artisan migrate --force

# Seed the database
print_status "Seeding the database..."
php artisan db:seed --force

# Clear and cache configuration
print_status "Clearing and caching configuration..."
php artisan config:clear
php artisan config:cache
php artisan route:clear
php artisan route:cache
php artisan view:clear
php artisan view:cache

# Set up Nginx configuration
print_status "Setting up Nginx configuration..."
sudo tee /etc/nginx/sites-available/mmgweb << EOF
server {
    listen 80;
    server_name ${DOMAIN_NAME} www.${DOMAIN_NAME};
    root /var/www/mmgweb/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php\$ {
        fastcgi_pass unix:/var/run/php/php${PHP_FPM_VERSION}-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

# Enable the site
print_status "Enabling Nginx site..."
sudo ln -sf /etc/nginx/sites-available/mmgweb /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default

# Test Nginx configuration
print_status "Testing Nginx configuration..."
if sudo nginx -t; then
    print_success "Nginx configuration is valid"
else
    print_error "Nginx configuration test failed"
    exit 1
fi

# Restart services
print_status "Restarting services..."
sudo systemctl restart php${PHP_FPM_VERSION}-fpm
sudo systemctl restart nginx

# Verify services are running
print_status "Verifying services..."
if service_running nginx; then
    print_success "Nginx is running"
else
    print_error "Nginx failed to start"
    exit 1
fi

if service_running php${PHP_FPM_VERSION}-fpm; then
    print_success "PHP-FPM is running"
else
    print_error "PHP-FPM failed to start"
    exit 1
fi

# Test application
print_status "Testing application..."
if curl -s -o /dev/null -w "%{http_code}" http://localhost | grep -q "200\|302"; then
    print_success "Application is responding"
else
    print_warning "Application test failed, but deployment may still be successful"
fi

# Set up SSL with Let's Encrypt (optional)
print_status "SSL Setup Instructions:"
echo "To enable SSL, run:"
echo "sudo apt install certbot python3-certbot-nginx"
echo "sudo certbot --nginx -d ${DOMAIN_NAME}"

print_success "Deployment completed successfully!"
echo ""
echo "🌐 Your MMG website should now be accessible at:"
echo "   Main site: http://${DOMAIN_NAME}"
echo "   Admin panel: http://${DOMAIN_NAME}/admin"
echo "   POS system: http://${DOMAIN_NAME}/pos"
echo "   Booking system: http://${DOMAIN_NAME}/booking"
echo ""
echo "🔧 Default admin credentials:"
echo "   Email: admin@mmg.mv"
echo "   Password: password"
echo ""
echo "⚠️  IMPORTANT: Change the admin password after first login!"
