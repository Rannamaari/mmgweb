# 🚀 Digital Ocean Deployment Guide - MMG Laravel App

## 📋 Pre-Deployment Requirements

### 1. Digital Ocean Droplet Setup
- **OS**: Ubuntu 22.04 LTS (recommended)
- **RAM**: Minimum 2GB (4GB+ recommended for production)
- **Storage**: Minimum 25GB SSD
- **Network**: Enable IPv6, monitoring, and backups

### 2. Database Setup
- **Digital Ocean Managed PostgreSQL** (recommended)
- Or self-hosted PostgreSQL 13+
- Ensure SSL is enabled and required

### 3. Domain Configuration
- Point `garage.micronet.mv` to your droplet's IP address
- Configure DNS with proper A records

## 🎯 Quick Deployment Steps

### Step 1: Connect to Your Droplet
```bash
# SSH into your droplet
ssh root@your_droplet_ip

# Update system
apt update && apt upgrade -y
```

### Step 2: Set Environment Variables
```bash
# Set your database password (REQUIRED)
export DB_PASSWORD="your_database_password_here"

# Optional: customize other settings
export DOMAIN_NAME="garage.micronet.mv"
export DB_HOST="your_db_host"
export DB_PORT="25060"
export DB_DATABASE="mmgweb"
export DB_USERNAME="doadmin"
```

### Step 3: Download and Run Deployment Script
```bash
# Download the deployment script
wget https://raw.githubusercontent.com/Rannamaari/mmgweb/main/deploy-digital-ocean.sh

# Make it executable
chmod +x deploy-digital-ocean.sh

# Run the deployment
./deploy-digital-ocean.sh
```

### Step 4: Enable SSL (Post-Deployment)
```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx -y

# Get SSL certificate
sudo certbot --nginx -d garage.micronet.mv -d www.garage.micronet.mv

# Test auto-renewal
sudo certbot renew --dry-run
```

## 🔧 What the Script Does

### System Configuration:
- ✅ Updates Ubuntu packages
- ✅ Installs Nginx, PHP 8.3/8.4, PostgreSQL client
- ✅ Installs Composer and Node.js
- ✅ Configures proper PHP extensions

### Application Setup:
- ✅ Clones the MMG repository
- ✅ Installs PHP dependencies (including Laravel Boost)
- ✅ Installs Node.js dependencies and builds assets
- ✅ Configures production environment
- ✅ Generates application key

### Database Configuration:
- ✅ Tests database connectivity
- ✅ Runs migrations with PostgreSQL boolean fixes
- ✅ Seeds initial roles and admin user
- ✅ Optimizes for production

### Web Server Setup:
- ✅ Configures Nginx with security headers
- ✅ Sets up PHP-FPM with proper settings
- ✅ Enables gzip compression
- ✅ Configures static file caching

### Security & Performance:
- ✅ Sets proper file permissions
- ✅ Caches Laravel configuration
- ✅ Optimizes Composer autoloader
- ✅ Configures security headers

## 🔐 Default Access Credentials

After deployment, you can access:

| Service | URL | Email | Password |
|---------|-----|-------|----------|
| **Admin Panel** | `https://garage.micronet.mv/admin` | `admin@mmg.mv` | `Admin@2024!` |
| **POS Manager** | `https://garage.micronet.mv/admin` | `posmanager@mmg.mv` | `PosManager@2024!` |
| **POS System** | `https://garage.micronet.mv/pos` | `posoperator@mmg.mv` | `PosOperator@2024!` |
| **Booking** | `https://garage.micronet.mv/booking` | `bookingmanager@mmg.mv` | `BookingManager@2024!` |

⚠️ **SECURITY WARNING**: Change all passwords immediately after first login!

## 🛠️ Post-Deployment Tasks

### 1. Security Hardening
```bash
# Change admin password
php artisan tinker
# Then in tinker: User::where('email', 'admin@mmg.mv')->first()->update(['password' => Hash::make('YourNewPassword')]);

# Configure firewall
ufw allow ssh
ufw allow 'Nginx Full'
ufw enable
```

### 2. Set Up Monitoring
```bash
# Check logs
tail -f /var/www/mmgweb/storage/logs/laravel.log

# Monitor services
systemctl status nginx
systemctl status php8.3-fpm
```

### 3. Database Backup Setup
```bash
# Create backup script
cat > /home/backup-db.sh << 'EOF'
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
pg_dump "postgresql://doadmin:$DB_PASSWORD@$DB_HOST:$DB_PORT/$DB_DATABASE?sslmode=require" > /home/backup_mmg_$DATE.sql
EOF

# Make it executable and add to cron
chmod +x /home/backup-db.sh
echo "0 2 * * * /home/backup-db.sh" | crontab -
```

## 🚨 Troubleshooting

### Common Issues:

**1. Database Connection Failed**
```bash
# Check database credentials
echo $DB_PASSWORD

# Test connection manually
psql "postgresql://doadmin:$DB_PASSWORD@$DB_HOST:$DB_PORT/$DB_DATABASE?sslmode=require"
```

**2. Permission Denied Errors**
```bash
# Fix file permissions
sudo chown -R www-data:www-data /var/www/mmgweb
sudo chmod -R 755 /var/www/mmgweb
sudo chmod -R 775 /var/www/mmgweb/storage
sudo chmod -R 775 /var/www/mmgweb/bootstrap/cache
```

**3. Nginx Configuration Error**
```bash
# Test Nginx config
sudo nginx -t

# Reload if valid
sudo systemctl reload nginx
```

**4. Application Not Loading**
```bash
# Check PHP-FPM status
systemctl status php8.3-fpm

# Check Laravel logs
tail -50 /var/www/mmgweb/storage/logs/laravel.log

# Clear cache
cd /var/www/mmgweb
php artisan config:clear
php artisan cache:clear
```

## 📊 Performance Optimization

### Enable Redis (Optional)
```bash
# Install Redis
sudo apt install redis-server -y
sudo systemctl enable redis-server

# Update .env
# CACHE_DRIVER=redis
# SESSION_DRIVER=redis
# QUEUE_CONNECTION=redis
```

### Database Indexing
The application includes optimized database indexes for:
- User authentication and permissions
- Product searches and filtering
- Invoice and booking queries

## 📞 Support

If you encounter issues:

1. **Check logs**: `/var/www/mmgweb/storage/logs/laravel.log`
2. **Test database**: Run database connectivity tests
3. **Verify services**: Ensure Nginx and PHP-FPM are running
4. **Check permissions**: Verify file ownership and permissions

## 🎉 Success Checklist

After deployment, verify:
- [ ] Website loads at `https://garage.micronet.mv`
- [ ] Admin panel accessible at `/admin`
- [ ] Can login with provided credentials
- [ ] POS system loads at `/pos`
- [ ] Booking system accessible at `/booking`
- [ ] Products can be added/edited (PostgreSQL boolean fix)
- [ ] User management works correctly
- [ ] SSL certificate is active
- [ ] All services are running

---

**🚀 Your MMG Laravel application is now ready for production use on Digital Ocean!**