#!/bin/bash

# MMG Debug Mode Setup Script
# This script enables debug mode for local development

echo "🔧 Setting up debug mode for local development..."

# Update .env file for debug mode
sed -i '' 's/APP_ENV=production/APP_ENV=local/' .env
sed -i '' 's/APP_DEBUG=false/APP_DEBUG=true/' .env
sed -i '' 's|APP_URL=https://your-domain.com|APP_URL=http://localhost:8000|' .env
sed -i '' 's/LOG_LEVEL=error/LOG_LEVEL=debug/' .env

echo "✅ Debug mode enabled!"
echo ""
echo "📋 Changes made:"
echo "   - APP_ENV: production → local"
echo "   - APP_DEBUG: false → true"
echo "   - APP_URL: https://your-domain.com → http://localhost:8000"
echo "   - LOG_LEVEL: error → debug"
echo ""
echo "🔄 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
echo ""
echo "🎉 Debug mode is now active!"
echo "   - Detailed error messages will be shown"
echo "   - Stack traces will be displayed"
echo "   - Development tools will be available"
echo ""
echo "⚠️  Remember to disable debug mode before production deployment!"
