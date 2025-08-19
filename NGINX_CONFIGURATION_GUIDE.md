# 🌐 MMG Nginx Configuration Guide

## 🎯 Overview

This guide explains the **enterprise-grade Nginx configuration** for your MMG website, including SSL, security, performance, and Laravel optimization.

---

## 🚀 Quick Setup

### **Automatic Configuration (Recommended)**

```bash
# The SSL setup script automatically configures Nginx
wget https://raw.githubusercontent.com/Rannamaari/mmgweb/main/ssl-setup.sh
chmod +x ssl-setup.sh
sudo ./ssl-setup.sh
```

### **Manual Configuration**

```bash
# Download the configuration
wget -O /etc/nginx/sites-available/mmgweb https://raw.githubusercontent.com/Rannamaari/mmgweb/main/nginx-config.conf

# Enable the site
ln -sf /etc/nginx/sites-available/mmgweb /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default

# Test and reload
nginx -t
systemctl reload nginx
```

---

## 🛡️ Security Features

### **SSL/TLS Configuration**

```nginx
# Modern SSL protocols and ciphers
ssl_protocols TLSv1.2 TLSv1.3;
ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-SHA384;

# SSL session optimization
ssl_session_cache shared:SSL:10m;
ssl_session_timeout 10m;
ssl_session_tickets off;

# OCSP stapling for better performance
ssl_stapling on;
ssl_stapling_verify on;
```

### **Security Headers**

```nginx
# Prevent clickjacking
add_header X-Frame-Options "SAMEORIGIN" always;

# Prevent MIME sniffing
add_header X-Content-Type-Options "nosniff" always;

# XSS protection
add_header X-XSS-Protection "1; mode=block" always;

# Control referrer information
add_header Referrer-Policy "strict-origin-when-cross-origin" always;

# Content Security Policy
add_header Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' data:; connect-src 'self'; frame-ancestors 'self';" always;

# Force HTTPS (HSTS)
add_header Strict-Transport-Security "max-age=31536000; includeSubDomains; preload" always;
```

### **File Access Protection**

```nginx
# Deny access to sensitive files
location ~ /\. {
    deny all;
    access_log off;
    log_not_found off;
}

# Deny access to configuration files
location ~* \.(env|log|sql|conf|ini|bak|backup|old|orig|save|swp|tmp)$ {
    deny all;
    access_log off;
    log_not_found off;
}

# Deny access to vendor and storage directories
location ~ ^/(vendor|storage|bootstrap/cache)/ {
    deny all;
    access_log off;
    log_not_found off;
}
```

---

## ⚡ Performance Features

### **Gzip Compression**

```nginx
gzip on;
gzip_vary on;
gzip_min_length 1024;
gzip_proxied any;
gzip_comp_level 6;
gzip_types
    text/plain
    text/css
    text/xml
    text/javascript
    application/json
    application/javascript
    application/xml+rss
    application/atom+xml
    image/svg+xml;
```

### **Static File Caching**

```nginx
# Cache static files for 1 year
location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
    expires 1y;
    add_header Cache-Control "public, immutable";
    access_log off;
}
```

### **HTTP/2 Support**

```nginx
# Enable HTTP/2 for better performance
listen 443 ssl http2;
listen [::]:443 ssl http2;
```

---

## 🔒 Rate Limiting

### **API Rate Limiting**

```nginx
# Define rate limiting zones
limit_req_zone $binary_remote_addr zone=api:10m rate=10r/s;
limit_req_zone $binary_remote_addr zone=login:10m rate=1r/s;

# Apply to specific locations
location /web-api/ {
    limit_req zone=api burst=50 nodelay;
}

location /admin {
    limit_req zone=api burst=20 nodelay;
}

location /pos {
    limit_req zone=api burst=30 nodelay;
}
```

---

## 🏗️ Laravel-Specific Configuration

### **PHP-FPM Configuration**

```nginx
# Upstream for PHP-FPM
upstream php-fpm {
    server unix:/var/run/php/php8.3-fpm.sock;
}

# PHP processing
location ~ \.php$ {
    try_files $uri =404;
    fastcgi_split_path_info ^(.+\.php)(/.+)$;
    fastcgi_pass php-fpm;
    fastcgi_index index.php;
    include fastcgi_params;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    fastcgi_param PATH_INFO $fastcgi_path_info;
    fastcgi_param HTTP_PROXY "";

    # Security headers for PHP
    fastcgi_hide_header X-Powered-By;
    fastcgi_read_timeout 300;
}
```

### **Laravel Routing**

```nginx
# Main location block for Laravel
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

---

## 🌐 Domain Configuration

### **HTTP to HTTPS Redirect**

```nginx
# HTTP Server (redirects to HTTPS)
server {
    listen 80;
    listen [::]:80;
    server_name garage.micronet.mv www.garage.micronet.mv;

    # Redirect all HTTP traffic to HTTPS
    return 301 https://$server_name$request_uri;
}
```

### **HTTPS Server**

```nginx
# HTTPS Server
server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name garage.micronet.mv www.garage.micronet.mv;

    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/garage.micronet.mv/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/garage.micronet.mv/privkey.pem;
}
```

---

## 📊 Monitoring & Logging

### **Access and Error Logs**

```nginx
# Logging configuration
access_log /var/log/nginx/mmgweb_access.log;
error_log /var/log/nginx/mmgweb_error.log;
```

### **Log Monitoring Commands**

```bash
# Monitor access logs
tail -f /var/log/nginx/mmgweb_access.log

# Monitor error logs
tail -f /var/log/nginx/mmgweb_error.log

# Check for errors
grep -i error /var/log/nginx/mmgweb_error.log

# Monitor SSL connections
grep -i ssl /var/log/nginx/mmgweb_access.log
```

---

## 🔧 Configuration Management

### **Backup Configuration**

```bash
# Backup current configuration
cp /etc/nginx/sites-available/mmgweb /etc/nginx/sites-available/mmgweb.backup

# Restore from backup
cp /etc/nginx/sites-available/mmgweb.backup /etc/nginx/sites-available/mmgweb
nginx -t
systemctl reload nginx
```

### **Test Configuration**

```bash
# Test Nginx configuration
nginx -t

# Test specific configuration file
nginx -t -c /etc/nginx/sites-available/mmgweb

# Check configuration syntax
nginx -T | grep -A 10 -B 10 "server_name"
```

---

## 🚨 Troubleshooting

### **Common Issues**

**Problem**: 502 Bad Gateway

```bash
# Check PHP-FPM status
systemctl status php8.3-fpm

# Check PHP-FPM socket
ls -la /var/run/php/php8.3-fpm.sock

# Restart PHP-FPM
systemctl restart php8.3-fpm
```

**Problem**: SSL Certificate Errors

```bash
# Check certificate validity
openssl x509 -in /etc/letsencrypt/live/garage.micronet.mv/fullchain.pem -text -noout

# Check certificate expiration
certbot certificates

# Renew certificate
certbot renew
```

**Problem**: Permission Denied

```bash
# Check file permissions
ls -la /var/www/mmgweb/public/

# Fix permissions
chown -R www-data:www-data /var/www/mmgweb
chmod -R 755 /var/www/mmgweb
chmod -R 775 /var/www/mmgweb/storage
```

---

## 📈 Performance Optimization

### **SSL Performance**

```nginx
# SSL session caching
ssl_session_cache shared:SSL:10m;
ssl_session_timeout 10m;

# OCSP stapling
ssl_stapling on;
ssl_stapling_verify on;
```

### **Static File Optimization**

```nginx
# Cache static files
location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
    expires 1y;
    add_header Cache-Control "public, immutable";
    access_log off;
}
```

### **Gzip Compression**

```nginx
# Enable compression for text files
gzip on;
gzip_vary on;
gzip_min_length 1024;
gzip_comp_level 6;
```

---

## 🔍 Configuration Validation

### **Test Your Configuration**

```bash
# Test Nginx configuration
nginx -t

# Test SSL configuration
curl -I https://garage.micronet.mv

# Test security headers
curl -I https://garage.micronet.mv | grep -E "(X-|Strict-|Content-)"

# Test SSL Labs
https://www.ssllabs.com/ssltest/analyze.html?d=garage.micronet.mv
```

### **Performance Testing**

```bash
# Test with Apache Bench
ab -n 1000 -c 10 https://garage.micronet.mv/

# Test with curl
curl -w "@curl-format.txt" -o /dev/null -s https://garage.micronet.mv/
```

---

## 📞 Quick Reference

### **Essential Commands**

```bash
# Test configuration
nginx -t

# Reload configuration
systemctl reload nginx

# Restart Nginx
systemctl restart nginx

# Check status
systemctl status nginx

# View configuration
nginx -T

# Monitor logs
tail -f /var/log/nginx/mmgweb_access.log
```

### **Configuration Files**

-   **Main config**: `/etc/nginx/nginx.conf`
-   **Site config**: `/etc/nginx/sites-available/mmgweb`
-   **SSL certificates**: `/etc/letsencrypt/live/garage.micronet.mv/`
-   **Logs**: `/var/log/nginx/mmgweb_*.log`

Your MMG website now has **enterprise-grade Nginx configuration** with SSL, security, and performance optimization! 🚀
