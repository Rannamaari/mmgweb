#!/bin/bash

# MMG Production Mode Setup Script
# This script enables production mode for deployment

echo "🚀 Setting up production mode..."

# Update .env file for production mode
sed -i '' 's/APP_ENV=local/APP_ENV=production/' .env
sed -i '' 's/APP_DEBUG=true/APP_DEBUG=false/' .env
sed -i '' 's|APP_URL=http://localhost:8000|APP_URL=https://garage.micronet.mv|' .env
sed -i '' 's/LOG_LEVEL=debug/LOG_LEVEL=error/' .env

echo "✅ Production mode enabled!"
echo ""
echo "📋 Changes made:"
echo "   - APP_ENV: local → production"
echo "   - APP_DEBUG: true → false"
echo "   - APP_URL: http://localhost:8000 → https://garage.micronet.mv"
echo "   - LOG_LEVEL: debug → error"
echo ""
echo "🔄 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
echo ""
echo "🎉 Production mode is now active!"
echo "   - Error messages are hidden for security"
echo "   - Performance optimizations are enabled"
echo "   - Ready for production deployment"
echo ""
echo "⚠️  Debug information is now hidden for security!"
