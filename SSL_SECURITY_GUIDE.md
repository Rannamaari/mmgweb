# 🔒 MMG SSL Security Guide

## 🎯 Overview

This guide covers **complete website security** for your MMG website, including SSL certificates, security headers, and best practices.

---

## 🚀 Quick SSL Setup

### **Option 1: Automated Setup (Recommended)**

```bash
# Download and run the SSL setup script
wget https://raw.githubusercontent.com/Rannamaari/mmgweb/main/ssl-setup.sh
chmod +x ssl-setup.sh
sudo ./ssl-setup.sh
```

### **Option 2: Manual Setup**

```bash
# Install Certbot
sudo apt update
sudo apt install -y certbot python3-certbot-nginx

# Obtain SSL certificate
sudo certbot --nginx -d garage.micronet.mv -d www.garage.micronet.mv

# Set up automatic renewal
sudo crontab -e
# Add this line: 0 12 * * * /usr/bin/certbot renew --quiet
```

---

## 🛡️ Security Features Applied

### **SSL/TLS Security**
- ✅ **Let's Encrypt SSL Certificate** (free, trusted)
- ✅ **Automatic renewal** (daily cron job)
- ✅ **HTTPS enforcement** (redirects HTTP to HTTPS)
- ✅ **HSTS headers** (HTTP Strict Transport Security)
- ✅ **Modern SSL configuration** (TLS 1.2+)

### **Security Headers**
- ✅ **X-Frame-Options**: Prevents clickjacking
- ✅ **X-Content-Type-Options**: Prevents MIME sniffing
- ✅ **X-XSS-Protection**: XSS protection
- ✅ **Referrer-Policy**: Controls referrer information
- ✅ **Content-Security-Policy**: Prevents XSS and injection attacks
- ✅ **Strict-Transport-Security**: Forces HTTPS

### **Nginx Security**
- ✅ **Secure server configuration**
- ✅ **Hidden server version**
- ✅ **Rate limiting** (optional)
- ✅ **Request size limits**

---

## 🔧 SSL Configuration Details

### **Certificate Information**
```bash
# View certificate details
sudo certbot certificates

# Test certificate renewal
sudo certbot renew --dry-run

# Check certificate expiration
openssl x509 -in /etc/letsencrypt/live/garage.micronet.mv/fullchain.pem -text -noout | grep "Not After"
```

### **Nginx SSL Configuration**
The script automatically configures Nginx with:
- **SSL certificate paths**
- **HTTP to HTTPS redirect**
- **Security headers**
- **Optimized SSL settings**

---

## 📊 SSL Testing & Monitoring

### **SSL Labs Test**
Test your SSL configuration:
```
https://www.ssllabs.com/ssltest/analyze.html?d=garage.micronet.mv
```

### **Manual Testing**
```bash
# Test HTTPS connection
curl -I https://garage.micronet.mv

# Test security headers
curl -I https://garage.micronet.mv | grep -E "(X-|Strict-|Content-)"

# Test certificate
openssl s_client -connect garage.micronet.mv:443 -servername garage.micronet.mv
```

### **Monitoring Commands**
```bash
# Check certificate status
sudo certbot certificates

# View Nginx SSL logs
sudo tail -f /var/log/nginx/access.log | grep SSL

# Monitor certificate expiration
sudo certbot renew --dry-run
```

---

## 🔐 Additional Security Measures

### **1. Firewall Configuration**
```bash
# Install and configure UFW
sudo apt install ufw
sudo ufw allow ssh
sudo ufw allow 'Nginx Full'
sudo ufw enable
```

### **2. Fail2ban Protection**
```bash
# Install Fail2ban
sudo apt install fail2ban

# Configure for Nginx
sudo cp /etc/fail2ban/jail.conf /etc/fail2ban/jail.local
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

### **3. Regular Security Updates**
```bash
# Set up automatic security updates
sudo apt install unattended-upgrades
sudo dpkg-reconfigure -plow unattended-upgrades
```

### **4. Database Security**
```bash
# Ensure database is only accessible locally
sudo netstat -tlnp | grep :5432
# Should show: tcp 127.0.0.1:5432
```

---

## 🚨 Security Checklist

### **SSL Certificate**
- [ ] SSL certificate installed and working
- [ ] HTTP redirects to HTTPS
- [ ] Certificate auto-renewal configured
- [ ] SSL Labs grade A or A+

### **Security Headers**
- [ ] X-Frame-Options: SAMEORIGIN
- [ ] X-Content-Type-Options: nosniff
- [ ] X-XSS-Protection: 1; mode=block
- [ ] Strict-Transport-Security: max-age=31536000
- [ ] Content-Security-Policy configured

### **Server Security**
- [ ] Firewall enabled (UFW)
- [ ] Fail2ban installed and configured
- [ ] Automatic security updates enabled
- [ ] SSH key authentication only
- [ ] Database not exposed externally

### **Application Security**
- [ ] Laravel debug mode disabled
- [ ] Admin password changed
- [ ] Regular backups configured
- [ ] Log monitoring enabled

---

## 🔍 Troubleshooting

### **SSL Certificate Issues**

**Problem**: Certificate not obtained
```bash
# Check domain accessibility
curl -I http://garage.micronet.mv

# Check DNS configuration
nslookup garage.micronet.mv

# Manual certificate request
sudo certbot --nginx -d garage.micronet.mv --debug
```

**Problem**: Certificate renewal failing
```bash
# Test renewal manually
sudo certbot renew --dry-run

# Check renewal logs
sudo tail -f /var/log/letsencrypt/letsencrypt.log
```

### **Nginx SSL Issues**

**Problem**: SSL handshake failed
```bash
# Check Nginx configuration
sudo nginx -t

# Check SSL certificate
sudo openssl x509 -in /etc/letsencrypt/live/garage.micronet.mv/fullchain.pem -text -noout

# Restart Nginx
sudo systemctl restart nginx
```

### **Security Header Issues**

**Problem**: Headers not showing
```bash
# Check Nginx configuration
sudo grep -r "security-headers" /etc/nginx/

# Reload Nginx
sudo systemctl reload nginx

# Test headers
curl -I https://garage.micronet.mv
```

---

## 📈 Performance Optimization

### **SSL Performance**
```bash
# Enable SSL session caching
# Add to Nginx config:
ssl_session_cache shared:SSL:10m;
ssl_session_timeout 10m;

# Enable OCSP stapling
ssl_stapling on;
ssl_stapling_verify on;
```

### **Security vs Performance**
- ✅ **SSL session caching** improves performance
- ✅ **HSTS** improves security (one-time setup)
- ✅ **Security headers** have minimal performance impact
- ✅ **Rate limiting** can be adjusted based on traffic

---

## 🆘 Emergency Procedures

### **Certificate Expired**
```bash
# Force certificate renewal
sudo certbot renew --force-renewal

# If that fails, reinstall certificate
sudo certbot --nginx -d garage.micronet.mv --force-renewal
```

### **Security Breach**
```bash
# Check for unauthorized access
sudo tail -f /var/log/nginx/access.log
sudo tail -f /var/log/auth.log

# Block suspicious IPs
sudo ufw deny from SUSPICIOUS_IP

# Check for malware
sudo apt install rkhunter
sudo rkhunter --check
```

### **Rollback SSL Changes**
```bash
# Restore previous Nginx configuration
sudo cp /etc/nginx/sites-available/mmgweb.backup /etc/nginx/sites-available/mmgweb

# Remove SSL certificate
sudo certbot delete --cert-name garage.micronet.mv

# Restart Nginx
sudo systemctl restart nginx
```

---

## 📞 Quick Reference

### **Essential Commands**
```bash
# SSL certificate status
sudo certbot certificates

# Test SSL configuration
curl -I https://garage.micronet.mv

# Check security headers
curl -I https://garage.micronet.mv | grep -E "(X-|Strict-|Content-)"

# Monitor logs
sudo tail -f /var/log/nginx/access.log
sudo tail -f /var/log/letsencrypt/letsencrypt.log

# Renew certificate manually
sudo certbot renew
```

### **Security Testing**
```bash
# SSL Labs test
https://www.ssllabs.com/ssltest/analyze.html?d=garage.micronet.mv

# Security headers test
https://securityheaders.com/?q=garage.micronet.mv

# SSL certificate test
https://www.ssllabs.com/ssltest/analyze.html?d=garage.micronet.mv
```

Your MMG website is now **enterprise-grade secure** with SSL certificates and comprehensive security measures! 🔒🚀
