#!/bin/bash

# MMG Auto-Deploy Script
# Run this script to pull latest changes and update the application

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

# Configuration
APP_DIR="/var/www/mmgweb"
BACKUP_DIR="/var/backups/mmgweb"
DATE=$(date +%Y%m%d_%H%M%S)

print_status "Starting MMG Auto-Deployment..."

# Check if we're in the correct directory
if [ ! -d "$APP_DIR" ]; then
    print_error "Application directory not found: $APP_DIR"
    exit 1
fi

cd "$APP_DIR"

# Create backup directory
sudo mkdir -p "$BACKUP_DIR"

# Backup current application
print_status "Creating backup of current application..."
sudo tar -czf "$BACKUP_DIR/mmgweb_backup_$DATE.tar.gz" \
    --exclude='.git' \
    --exclude='node_modules' \
    --exclude='vendor' \
    --exclude='storage/logs/*' \
    --exclude='storage/framework/cache/*' \
    .

print_success "Backup created: mmgweb_backup_$DATE.tar.gz"

# Pull latest changes
print_status "Pulling latest changes from Git..."
git fetch origin
git reset --hard origin/main

# Install/update PHP dependencies
print_status "Updating PHP dependencies..."
composer install --no-dev --optimize-autoloader

# Install/update Node.js dependencies and build assets
print_status "Updating Node.js dependencies and building assets..."
npm install
npm run build

# Clear all caches
print_status "Clearing application caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Run database migrations
print_status "Running database migrations..."
php artisan migrate --force

# Cache configuration for production
print_status "Caching configuration for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
print_status "Setting proper permissions..."
sudo chown -R www-data:www-data "$APP_DIR"
sudo chmod -R 755 "$APP_DIR"
sudo chmod -R 775 "$APP_DIR/storage"
sudo chmod -R 775 "$APP_DIR/bootstrap/cache"

# Restart services
print_status "Restarting services..."
sudo systemctl restart php8.3-fpm
sudo systemctl restart nginx

# Test application
print_status "Testing application..."
if curl -s -o /dev/null -w "%{http_code}" http://localhost | grep -q "200\|302"; then
    print_success "Application is responding correctly"
else
    print_warning "Application test failed, but deployment may still be successful"
fi

# Clean up old backups (keep last 5)
print_status "Cleaning up old backups..."
cd "$BACKUP_DIR"
ls -t mmgweb_backup_*.tar.gz | tail -n +6 | xargs -r sudo rm

print_success "Auto-deployment completed successfully!"
echo ""
echo "🔄 Latest changes have been deployed"
echo "📦 Backup created: mmgweb_backup_$DATE.tar.gz"
echo "🌐 Your application should be updated at: $(grep APP_URL .env | cut -d'=' -f2)"
echo ""
echo "📊 To monitor the application:"
echo "   tail -f $APP_DIR/storage/logs/laravel.log"
