# 🔐 MMG Password Security Guide

## 🎯 Overview

This guide covers **secure password management** for your MMG website, including changing the default admin password and implementing security best practices.

---

## 🚨 **URGENT: Change Default Password**

The default admin password `password` is **extremely weak** and must be changed immediately in production!

---

## 🚀 Quick Password Change

### **Option 1: Automated Script (Recommended)**

```bash
# Download and run the password change script
wget https://raw.githubusercontent.com/Rannamaari/mmgweb/main/change-admin-password.sh
chmod +x change-admin-password.sh
./change-admin-password.sh
```

### **Option 2: Laravel Artisan Command**

```bash
# Generate a secure password automatically
php artisan admin:change-password --generate

# Or enter your own password
php artisan admin:change-password
```

### **Option 3: Manual Tinker**

```bash
# Open Laravel Tinker
php artisan tinker

# Change password manually
$user = App\Models\User::where('email', 'admin@mmg.mv')->first();
$user->password = Hash::make('YourSecurePassword123!');
$user->save();
exit
```

---

## 🔒 Password Requirements

### **Minimum Security Standards**
- ✅ **At least 12 characters** long
- ✅ **Uppercase letters** (A-Z)
- ✅ **Lowercase letters** (a-z)
- ✅ **Numbers** (0-9)
- ✅ **Special characters** (!@#$%^&*()_+-=[]{}|;:,.<>?)

### **Example Strong Passwords**
```
✅ K9#mN2$pL5@vX8!
✅ SecurePass2024!@#
✅ MMG@dmin2024#Secure
✅ MicroMoto@Garage2024!
```

### **Avoid Weak Passwords**
```
❌ password
❌ admin123
❌ 123456789
❌ qwerty
❌ admin@mmg.mv
❌ mmg2024
```

---

## 🛡️ Password Security Best Practices

### **1. Use a Password Manager**
- **Bitwarden** (free, open-source)
- **1Password** (premium)
- **LastPass** (premium)
- **KeePass** (free, local)

### **2. Enable Two-Factor Authentication (2FA)**
```bash
# Install 2FA package (if not already installed)
composer require laravel/fortify

# Configure 2FA in your admin panel
# This adds an extra layer of security
```

### **3. Regular Password Rotation**
- **Change admin password** every 90 days
- **Use different passwords** for different services
- **Never reuse passwords** across accounts

### **4. Secure Password Storage**
- **Never store passwords** in plain text
- **Use password managers** for secure storage
- **Enable encryption** on all devices
- **Backup passwords** securely

---

## 🔧 Advanced Security Measures

### **1. Password Policy Enforcement**

Add to your Laravel application:

```php
// In User model
protected static function boot()
{
    parent::boot();
    
    static::saving(function ($user) {
        if ($user->isDirty('password')) {
            // Enforce password policy
            if (strlen($user->password) < 12) {
                throw new \Exception('Password must be at least 12 characters');
            }
            
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/', $user->password)) {
                throw new \Exception('Password must contain uppercase, lowercase, number, and special character');
            }
        }
    });
}
```

### **2. Login Attempt Limiting**

```php
// In auth configuration
'throttle' => [
    'attempts' => 5,
    'decay_minutes' => 15,
],
```

### **3. Session Security**

```php
// In config/session.php
'lifetime' => 120, // 2 hours
'expire_on_close' => true,
'secure' => true, // HTTPS only
'http_only' => true,
'same_site' => 'lax',
```

---

## 📊 Password Security Checklist

### **Immediate Actions**
- [ ] **Change default admin password** (URGENT)
- [ ] **Use strong password** (12+ characters, mixed case, numbers, symbols)
- [ ] **Save password securely** (password manager)
- [ ] **Test new password** (login to admin panel)
- [ ] **Remove password from scripts** (if any)

### **Ongoing Security**
- [ ] **Enable 2FA** for admin accounts
- [ ] **Set up login notifications**
- [ ] **Monitor login attempts**
- [ ] **Regular password rotation** (90 days)
- [ ] **Secure password storage**
- [ ] **Backup password securely**

### **Advanced Security**
- [ ] **Implement password policy** in code
- [ ] **Set up login attempt limiting**
- [ ] **Configure session security**
- [ ] **Enable audit logging**
- [ ] **Regular security reviews**

---

## 🚨 Emergency Procedures

### **If Password is Compromised**

1. **Immediately change password**
   ```bash
   php artisan admin:change-password --generate
   ```

2. **Check for unauthorized access**
   ```bash
   # Check login logs
   tail -f /var/www/mmgweb/storage/logs/laravel.log | grep -i login
   
   # Check admin access
   grep -i "admin@mmg.mv" /var/log/nginx/mmgweb_access.log
   ```

3. **Review recent changes**
   ```bash
   # Check recent database changes
   php artisan tinker --execute="
       echo 'Recent user updates:';
       App\Models\User::where('updated_at', '>=', now()->subDays(7))->get(['email', 'updated_at']);
   "
   ```

4. **Enable additional security**
   - Enable 2FA immediately
   - Review all admin accounts
   - Check for suspicious activity

### **If You Forget the Password**

1. **Reset via database** (emergency only)
   ```bash
   php artisan tinker --execute="
       \$user = App\Models\User::where('email', 'admin@mmg.mv')->first();
       \$user->password = Hash::make('NewSecurePassword123!');
       \$user->save();
       echo 'Password reset to: NewSecurePassword123!';
   "
   ```

2. **Create new admin user**
   ```bash
   php artisan tinker --execute="
       App\Models\User::create([
           'name' => 'Emergency Admin',
           'email' => 'emergency@mmg.mv',
           'password' => Hash::make('EmergencyPass123!'),
           'email_verified_at' => now()
       ]);
       echo 'Emergency admin created: emergency@mmg.mv / EmergencyPass123!';
   "
   ```

---

## 📞 Quick Reference

### **Essential Commands**
```bash
# Change admin password (generate)
php artisan admin:change-password --generate

# Change admin password (manual)
php artisan admin:change-password

# Check admin user
php artisan tinker --execute="App\Models\User::where('email', 'admin@mmg.mv')->first(['email', 'updated_at']);"

# Test admin login
curl -I https://garage.micronet.mv/admin
```

### **Password Requirements**
- **Length**: 12+ characters
- **Uppercase**: A-Z
- **Lowercase**: a-z
- **Numbers**: 0-9
- **Special**: !@#$%^&*()_+-=[]{}|;:,.<>?

### **Security Recommendations**
1. **Use password manager**
2. **Enable 2FA**
3. **Regular rotation** (90 days)
4. **Monitor access**
5. **Secure storage**

---

## 🔍 Password Security Testing

### **Test Password Strength**
```bash
# Test with curl (replace with your password)
curl -X POST https://garage.micronet.mv/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@mmg.mv","password":"YourPassword"}'
```

### **Check Password Hash**
```bash
# Verify password is properly hashed
php artisan tinker --execute="
    \$user = App\Models\User::where('email', 'admin@mmg.mv')->first();
    echo 'Password hash: ' . \$user->password;
    echo 'Hash length: ' . strlen(\$user->password);
"
```

Your MMG website is now **secure** with proper password management! 🔐🚀
