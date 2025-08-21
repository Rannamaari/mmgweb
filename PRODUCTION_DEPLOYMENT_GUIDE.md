# 🚀 MMG Production Deployment Guide

## 📋 Pre-Deployment Checklist

### ✅ **Before Running the Script:**

1. **Ensure you're on the production server** (DigitalOcean droplet)
2. **Verify you're in the correct directory:** `/var/www/mmgweb`
3. **Check database connection** is working
4. **Backup current data** if needed

## 🔧 **Step-by-Step Deployment**

### **Step 1: Navigate to Project Directory**

```bash
cd /var/www/mmgweb
```

### **Step 2: Pull Latest Code**

```bash
git pull origin main
```

### **Step 3: Install Dependencies**

```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

### **Step 4: Set Production Environment**

```bash
# Ensure APP_ENV is set to production
export APP_ENV=production
```

### **Step 5: Run the User Management Deployment Script**

```bash
./deploy-users-production.sh
```

### **Step 6: Set Proper Permissions**

```bash
sudo chown -R www-data:www-data /var/www/mmgweb
sudo chmod -R 755 /var/www/mmgweb
sudo chmod -R 775 /var/www/mmgweb/storage
sudo chmod -R 775 /var/www/mmgweb/bootstrap/cache
```

### **Step 7: Restart Services**

```bash
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
```

## 🎯 **What the Script Does**

### **Database Setup:**

-   ✅ Runs migrations
-   ✅ Converts permissions column to JSONB (PostgreSQL compatibility)
-   ✅ Creates all roles (Super Admin, POS Manager, POS Operator, Booking Manager)

### **User Creation:**

-   ✅ Creates/updates all production users
-   ✅ Assigns appropriate roles
-   ✅ Sets secure passwords
-   ✅ Verifies setup

### **Production Users Created:**

| Role                | Email                   | Password               | Access Level         |
| ------------------- | ----------------------- | ---------------------- | -------------------- |
| **Super Admin**     | `admin@mmg.mv`          | `Admin@2024!`          | Full access          |
| **POS Manager**     | `posmanager@mmg.mv`     | `PosManager@2024!`     | POS + Admin features |
| **POS Operator**    | `posoperator@mmg.mv`    | `PosOperator@2024!`    | Basic POS operations |
| **Booking Manager** | `bookingmanager@mmg.mv` | `BookingManager@2024!` | Booking management   |

## 🔐 **Security Notes**

### **⚠️ IMPORTANT:**

1. **Change passwords immediately** after first login
2. **These are production credentials** - keep them secure
3. **Use strong passwords** for production
4. **Consider enabling 2FA** for admin accounts

### **🔒 Recommended Password Changes:**

```bash
# After deployment, change passwords using:
php artisan tinker
# Then update each user's password
```

## 🌐 **Access URLs**

-   **Admin Panel:** `https://garage.micronet.mv/admin`
-   **POS System:** `https://garage.micronet.mv/pos`
-   **Booking System:** `https://garage.micronet.mv/booking`
-   **Main Website:** `https://garage.micronet.mv`

## 🛠️ **Troubleshooting**

### **If Script Fails:**

1. **Check database connection:**

    ```bash
    php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database connected!';"
    ```

2. **Check permissions:**

    ```bash
    ls -la /var/www/mmgweb
    ```

3. **Check logs:**
    ```bash
    tail -f /var/www/mmgweb/storage/logs/laravel.log
    ```

### **If Users Can't Login:**

1. **Verify roles exist:**

    ```bash
    php artisan tinker --execute="echo 'Roles: ' . \App\Models\Role::count();"
    ```

2. **Check user permissions:**
    ```bash
    php artisan tinker --execute="\$user = \App\Models\User::where('email', 'admin@mmg.mv')->first(); echo 'Can access admin: ' . (\$user->hasPermission('admin.access') ? 'YES' : 'NO');"
    ```

## 📞 **Support**

If you encounter issues:

1. Check the Laravel logs: `/var/www/mmgweb/storage/logs/laravel.log`
2. Verify database connectivity
3. Ensure all services are running
4. Check file permissions

## 🎉 **Success Indicators**

After successful deployment, you should see:

-   ✅ All 4 roles created
-   ✅ All 4 users created with correct roles
-   ✅ Permission system working
-   ✅ Admin panel accessible
-   ✅ No errors in logs

---

**🚀 Ready to deploy! Run the script and enjoy your new user management system!**
