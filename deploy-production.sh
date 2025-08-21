#!/bin/bash

# Production Deployment Script for MMG Web
# Run this on your DigitalOcean droplet

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Functions
print_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
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

# Check if running as root
if [[ $EUID -eq 0 ]]; then
   print_error "This script should not be run as root"
   exit 1
fi

print_info "🚀 Starting MMG Web Production Deployment..."

# Navigate to project directory
cd /var/www/mmgweb

print_info "📂 Current directory: $(pwd)"

# Check if git repository exists
if [ ! -d ".git" ]; then
    print_error "Git repository not found. Please clone the repository first."
    exit 1
fi

# Pull latest code
print_info "📥 Pulling latest code from Git..."
git pull origin main
print_success "Code updated successfully"

# Install PHP dependencies
print_info "📦 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader
print_success "PHP dependencies installed"

# Install Node.js dependencies and build assets
print_info "📦 Installing Node.js dependencies..."
npm install
print_success "Node.js dependencies installed"

print_info "🔨 Building assets..."
npm run build
print_success "Assets built successfully"

# Run database migrations
print_info "🗄️  Running database migrations..."
php artisan migrate --force
print_success "Database migrations completed"

# Clear all caches
print_info "🧹 Clearing application caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
print_success "Caches cleared"

# Set proper permissions
print_info "🔐 Setting file permissions..."
sudo chown -R www-data:www-data /var/www/mmgweb
sudo chmod -R 755 /var/www/mmgweb
sudo chmod -R 775 /var/www/mmgweb/storage
sudo chmod -R 775 /var/www/mmgweb/bootstrap/cache
print_success "Permissions set correctly"

# Restart services
print_info "🔄 Restarting web services..."
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
print_success "Services restarted"

# Check service status
print_info "🔍 Checking service status..."
if systemctl is-active --quiet nginx; then
    print_success "Nginx is running"
else
    print_error "Nginx is not running"
fi

if systemctl is-active --quiet php8.2-fpm; then
    print_success "PHP-FPM is running"
else
    print_error "PHP-FPM is not running"
fi

print_success "🎉 Deployment completed successfully!"
print_info "🌐 Your application should be available at: https://garage.micronet.mv"
print_info "📊 Admin panel: https://garage.micronet.mv/admin"
print_info "📋 Booking page: https://garage.micronet.mv/booking"
