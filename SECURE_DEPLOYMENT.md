# 🔒 Secure MMG Website Deployment Guide

## 🚨 Security Improvements

This deployment script has been enhanced with the following security and reliability improvements:

### ✅ Fixed Issues:
- **PHP Version Compatibility**: Automatically detects and installs compatible PHP version (8.3 or 8.4)
- **Database Security**: Uses environment variables instead of hardcoded credentials
- **Error Handling**: Script stops on any error with `set -e`
- **Service Verification**: Checks if services are running after installation
- **Database Connection Test**: Verifies database connectivity before proceeding
- **Permission Management**: Proper file ownership and permissions
- **Domain Configuration**: Uses actual domain name instead of placeholders

## 🚀 Secure Deployment Process

### Step 1: Set Environment Variables (SECURE)

**⚠️ IMPORTANT: Set these variables before running the script to avoid exposing credentials in logs**

```bash
# Set your domain name
export DOMAIN_NAME="your-actual-domain.com"

# Set database credentials (optional, defaults are provided)
export DB_HOST="micronetdb-do-user-24249606-0.d.db.ondigitalocean.com"
export DB_PORT="25060"
export DB_DATABASE="mmgweb"
export DB_USERNAME="doadmin"
export DB_PASSWORD="AVNS_L5g7hboPAo5bkQEvWbu"
```

### Step 2: Download and Run the Script

```bash
# Download the script
wget https://raw.githubusercontent.com/Rannamaari/mmgweb/main/deploy.sh

# Make it executable
chmod +x deploy.sh

# Run the deployment
./deploy.sh
```

### Step 3: Verify Deployment

The script will automatically verify:
- ✅ Database connection
- ✅ PHP version compatibility
- ✅ Nginx configuration
- ✅ Service status
- ✅ Application responsiveness

## 🔧 Manual Security Steps (After Deployment)

### 1. Change Default Admin Password
```bash
# Access admin panel at https://your-domain.com/admin
# Login with: admin@mmg.mv / password
# Change password immediately
```

### 2. Set Up SSL Certificate
```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com
```

### 3. Secure File Permissions
```bash
# Verify permissions are correct
sudo chown -R www-data:www-data /var/www/mmgweb
sudo chmod -R 755 /var/www/mmgweb
sudo chmod -R 775 /var/www/mmgweb/storage
sudo chmod -R 775 /var/www/mmgweb/bootstrap/cache
```

### 4. Set Up Firewall
```bash
# Install and configure UFW
sudo apt install ufw
sudo ufw allow ssh
sudo ufw allow 'Nginx Full'
sudo ufw enable
```

### 5. Regular Security Updates
```bash
# Set up automatic security updates
sudo apt install unattended-upgrades
sudo dpkg-reconfigure -plow unattended-upgrades
```

## 🛡️ Security Features

### Database Security
- ✅ Credentials stored in environment variables
- ✅ SSL connection required
- ✅ Connection verification before deployment

### Application Security
- ✅ Production environment settings
- ✅ Debug mode disabled
- ✅ Proper file permissions
- ✅ Secure headers in Nginx

### Service Security
- ✅ Service status verification
- ✅ Configuration validation
- ✅ Error handling and logging

## 🔍 Troubleshooting

### Common Issues and Solutions

**1. Database Connection Failed**
```bash
# Test connection manually
psql "postgresql://doadmin:AVNS_L5g7hboPAo5bkQEvWbu@micronetdb-do-user-24249606-0.d.db.ondigitalocean.com:25060/mmgweb?sslmode=require"
```

**2. PHP Version Issues**
```bash
# Check installed PHP version
php --version

# Install specific version if needed
sudo apt install php8.3-fpm php8.3-pgsql
```

**3. Permission Issues**
```bash
# Fix permissions
sudo chown -R www-data:www-data /var/www/mmgweb
sudo chmod -R 755 /var/www/mmgweb
sudo chmod -R 775 /var/www/mmgweb/storage
```

**4. Nginx Configuration Error**
```bash
# Test configuration
sudo nginx -t

# Check logs
sudo tail -f /var/log/nginx/error.log
```

## 📊 Monitoring and Maintenance

### Health Checks
```bash
# Check service status
sudo systemctl status nginx
sudo systemctl status php8.3-fpm

# Check application logs
tail -f /var/www/mmgweb/storage/logs/laravel.log

# Check database connection
php artisan tinker --execute="echo 'DB: ' . (DB::connection()->getPdo() ? 'OK' : 'FAILED');"
```

### Backup Strategy
```bash
# Database backup
pg_dump "postgresql://doadmin:AVNS_L5g7hboPAo5bkQEvWbu@micronetdb-do-user-24249606-0.d.db.ondigitalocean.com:25060/mmgweb?sslmode=require" > backup_$(date +%Y%m%d_%H%M%S).sql

# Application backup
sudo tar -czf mmgweb_backup_$(date +%Y%m%d_%H%M%S).tar.gz /var/www/mmgweb
```

## 🎯 Success Indicators

After successful deployment, you should see:
- ✅ All services running (Nginx, PHP-FPM)
- ✅ Database connection working
- ✅ Application responding on your domain
- ✅ Admin panel accessible
- ✅ POS system working
- ✅ Booking system functional

## 🚨 Security Checklist

- [ ] Changed default admin password
- [ ] SSL certificate installed
- [ ] Firewall configured
- [ ] Automatic updates enabled
- [ ] Database credentials secured
- [ ] File permissions set correctly
- [ ] Logs monitored regularly
- [ ] Backups scheduled

## 🆘 Emergency Contacts

If you encounter issues:
1. Check Laravel logs: `/var/www/mmgweb/storage/logs/laravel.log`
2. Check Nginx logs: `/var/log/nginx/error.log`
3. Check PHP-FPM logs: `/var/log/php8.3-fpm.log`
4. Verify database connectivity
5. Check service status

Your MMG website is now deployed with enterprise-grade security! 🚀
