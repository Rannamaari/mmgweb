# 🚀 MMG Auto-Deployment Guide

## 📋 Overview

You now have **3 deployment options** for your MMG website:

1. **🔄 Manual Auto-Deploy** - Run a script to pull latest changes
2. **🤖 GitHub Actions** - Automatic deployment on every push
3. **📦 One-Time Setup** - Initial deployment (already done)

---

## 🔄 Option 1: Manual Auto-Deploy (Recommended for now)

### Setup on Your Server:

```bash
# Download the auto-deploy script
wget https://raw.githubusercontent.com/Rannamaari/mmgweb/main/auto-deploy.sh

# Make it executable
chmod +x auto-deploy.sh

# Move to a convenient location
sudo mv auto-deploy.sh /usr/local/bin/mmg-deploy
```

### Usage:

```bash
# Deploy latest changes
sudo mmg-deploy
```

### What it does:
- ✅ Creates backup before deployment
- ✅ Pulls latest changes from Git
- ✅ Updates dependencies (PHP & Node.js)
- ✅ Runs database migrations
- ✅ Clears and rebuilds caches
- ✅ Restarts services
- ✅ Tests application
- ✅ Cleans old backups

---

## 🤖 Option 2: GitHub Actions (Fully Automatic)

### Setup GitHub Secrets:

1. **Go to your GitHub repository**: `https://github.com/Rannamaari/mmgweb`
2. **Click Settings** → **Secrets and variables** → **Actions**
3. **Add these secrets**:

```
DROPLET_HOST=your-server-ip
DROPLET_USER=root
DROPLET_SSH_KEY=your-private-ssh-key
```

### How to get your SSH key:

```bash
# On your local machine, generate SSH key if you don't have one
ssh-keygen -t rsa -b 4096 -C "your-email@example.com"

# Copy the public key to your server
ssh-copy-id root@your-server-ip

# Copy the private key content for GitHub
cat ~/.ssh/id_rsa
```

### What happens:
- ✅ **Every push to main branch** triggers deployment
- ✅ **Automatic backup** before deployment
- ✅ **Zero downtime** deployment
- ✅ **Email notifications** on success/failure

---

## 🛠️ Option 3: Quick Manual Deploy

If you just want to quickly update your site:

```bash
cd /var/www/mmgweb

# Pull latest changes
git pull origin main

# Update dependencies
composer install --no-dev --optimize-autoloader
npm install && npm run build

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run migrations
php artisan migrate --force

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart services
sudo systemctl restart php8.3-fpm
sudo systemctl restart nginx
```

---

## 📊 Deployment Monitoring

### Check deployment status:

```bash
# Check if services are running
sudo systemctl status nginx
sudo systemctl status php8.3-fpm

# Check application logs
tail -f /var/www/mmgweb/storage/logs/laravel.log

# Check recent deployments
ls -la /var/backups/mmgweb/
```

### Test your application:

```bash
# Test homepage
curl -I http://your-domain.com

# Test admin panel
curl -I http://your-domain.com/admin

# Test POS system
curl -I http://your-domain.com/pos
```

---

## 🔧 Troubleshooting

### If deployment fails:

1. **Check logs**:
   ```bash
   tail -f /var/www/mmgweb/storage/logs/laravel.log
   sudo journalctl -u nginx -f
   sudo journalctl -u php8.3-fpm -f
   ```

2. **Restore from backup**:
   ```bash
   cd /var/www/mmgweb
   sudo tar -xzf /var/backups/mmgweb/mmgweb_backup_YYYYMMDD_HHMMSS.tar.gz
   sudo chown -R www-data:www-data /var/www/mmgweb
   sudo systemctl restart php8.3-fpm nginx
   ```

3. **Manual rollback**:
   ```bash
   cd /var/www/mmgweb
   git log --oneline -5
   git reset --hard HEAD~1
   composer install --no-dev --optimize-autoloader
   php artisan config:cache
   sudo systemctl restart php8.3-fpm nginx
   ```

---

## 🎯 Recommended Workflow

### For Development:
1. **Make changes** locally
2. **Test** on your local environment
3. **Commit and push** to GitHub
4. **Run manual deploy**: `sudo mmg-deploy`

### For Production (when ready):
1. **Set up GitHub Actions** with secrets
2. **Push to main branch** = automatic deployment
3. **Monitor** deployment status in GitHub Actions tab

---

## 🚨 Security Notes

- ✅ **Backups are created** before every deployment
- ✅ **Only main branch** triggers deployment
- ✅ **SSH keys** are encrypted in GitHub secrets
- ✅ **Services are restarted** after deployment
- ✅ **Permissions are set** correctly

---

## 📞 Quick Commands Reference

```bash
# Deploy latest changes
sudo mmg-deploy

# Check deployment status
sudo systemctl status nginx php8.3-fpm

# View logs
tail -f /var/www/mmgweb/storage/logs/laravel.log

# List backups
ls -la /var/backups/mmgweb/

# Quick update (no backup)
cd /var/www/mmgweb && git pull && composer install && npm run build && php artisan migrate --force
```

Your MMG website now has **enterprise-grade deployment automation**! 🚀
