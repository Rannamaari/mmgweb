#!/bin/bash

# MMG SSL Setup Script
# This script sets up SSL certificates for garage.micronet.mv

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

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
DOMAIN="garage.micronet.mv"
EMAIL="admin@micronet.mv"

print_status "Setting up SSL certificates for $DOMAIN..."

# Check if running as root
if [ "$EUID" -ne 0 ]; then
    print_error "This script must be run as root (use sudo)"
    exit 1
fi

# Update system packages
print_status "Updating system packages..."
apt update

# Install Certbot and Nginx plugin
print_status "Installing Certbot and Nginx plugin..."
apt install -y certbot python3-certbot-nginx

# Check if Nginx is running
if ! systemctl is-active --quiet nginx; then
    print_error "Nginx is not running. Please start Nginx first."
    exit 1
fi

# Check if domain is accessible
print_status "Checking domain accessibility..."
if ! curl -s -o /dev/null -w "%{http_code}" "http://$DOMAIN" | grep -q "200\|302"; then
    print_warning "Domain $DOMAIN might not be accessible. Make sure DNS is configured correctly."
    echo "Press Enter to continue anyway, or Ctrl+C to abort..."
    read -r
fi

# Obtain SSL certificate
print_status "Obtaining SSL certificate from Let's Encrypt..."
if certbot --nginx -d "$DOMAIN" -d "www.$DOMAIN" --email "$EMAIL" --agree-tos --non-interactive; then
    print_success "SSL certificate obtained successfully!"
else
    print_error "Failed to obtain SSL certificate. Please check your domain configuration."
    exit 1
fi

# Configure automatic renewal
print_status "Setting up automatic certificate renewal..."
if ! crontab -l 2>/dev/null | grep -q "certbot renew"; then
    (crontab -l 2>/dev/null; echo "0 12 * * * /usr/bin/certbot renew --quiet") | crontab -
    print_success "Automatic renewal scheduled (daily at 12:00 PM)"
else
    print_warning "Automatic renewal already configured"
fi

# Test SSL configuration
print_status "Testing SSL configuration..."
if curl -s -o /dev/null -w "%{http_code}" "https://$DOMAIN" | grep -q "200\|302"; then
    print_success "HTTPS is working correctly!"
else
    print_warning "HTTPS test failed. Please check the configuration manually."
fi

# Security hardening
print_status "Applying security headers..."
cat > /etc/nginx/snippets/security-headers.conf << 'EOF'
# Security Headers
add_header X-Frame-Options "SAMEORIGIN" always;
add_header X-Content-Type-Options "nosniff" always;
add_header X-XSS-Protection "1; mode=block" always;
add_header Referrer-Policy "strict-origin-when-cross-origin" always;
add_header Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' data:; connect-src 'self'; frame-ancestors 'self';" always;
add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
EOF

# Update Nginx configuration with security headers
if [ -f "/etc/nginx/sites-available/mmgweb" ]; then
    print_status "Updating Nginx configuration with security headers..."
    sed -i '/add_header X-Frame-Options/d' /etc/nginx/sites-available/mmgweb
    sed -i '/add_header X-Content-Type-Options/d' /etc/nginx/sites-available/mmgweb
    sed -i '/include snippets\/security-headers.conf;/d' /etc/nginx/sites-available/mmgweb
    
    # Add security headers include
    sed -i '/server {/a\    include snippets/security-headers.conf;' /etc/nginx/sites-available/mmgweb
fi

# Test Nginx configuration
print_status "Testing Nginx configuration..."
if nginx -t; then
    print_success "Nginx configuration is valid"
else
    print_error "Nginx configuration test failed"
    exit 1
fi

# Reload Nginx
print_status "Reloading Nginx..."
systemctl reload nginx

# SSL Labs test
print_status "SSL configuration completed!"
echo ""
echo "🔒 SSL Certificate Information:"
echo "   Domain: $DOMAIN"
echo "   Certificate: Let's Encrypt"
echo "   Auto-renewal: Enabled (daily at 12:00 PM)"
echo ""
echo "🌐 Test your SSL configuration:"
echo "   https://www.ssllabs.com/ssltest/analyze.html?d=$DOMAIN"
echo ""
echo "📊 Monitor certificate status:"
echo "   sudo certbot certificates"
echo "   sudo certbot renew --dry-run"
echo ""
echo "🛡️ Security headers have been applied"
echo "   X-Frame-Options, X-Content-Type-Options, X-XSS-Protection"
echo "   Referrer-Policy, Content-Security-Policy, HSTS"
echo ""
print_success "SSL setup completed successfully!"
