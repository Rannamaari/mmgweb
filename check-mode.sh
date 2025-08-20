#!/bin/bash

# MMG Mode Status Check Script
# This script shows the current application mode

echo "🔍 Checking MMG Application Mode..."
echo ""

# Read current settings
APP_ENV=$(grep "APP_ENV=" .env | cut -d'=' -f2)
APP_DEBUG=$(grep "APP_DEBUG=" .env | cut -d'=' -f2)
APP_URL=$(grep "APP_URL=" .env | cut -d'=' -f2)
LOG_LEVEL=$(grep "LOG_LEVEL=" .env | cut -d'=' -f2)

echo "📋 Current Settings:"
echo "   APP_ENV: $APP_ENV"
echo "   APP_DEBUG: $APP_DEBUG"
echo "   APP_URL: $APP_URL"
echo "   LOG_LEVEL: $LOG_LEVEL"
echo ""

if [ "$APP_ENV" = "local" ] && [ "$APP_DEBUG" = "true" ]; then
    echo "🎯 Mode: LOCAL DEVELOPMENT (Debug Enabled)"
    echo "   ✅ Detailed error messages will be shown"
    echo "   ✅ Stack traces will be displayed"
    echo "   ✅ Development tools are available"
elif [ "$APP_ENV" = "production" ] && [ "$APP_DEBUG" = "false" ]; then
    echo "🚀 Mode: PRODUCTION (Debug Disabled)"
    echo "   🔒 Error messages are hidden for security"
    echo "   ⚡ Performance optimizations are enabled"
    echo "   🌐 Ready for production deployment"
else
    echo "⚠️  Mode: MIXED/CUSTOM"
    echo "   Check your .env file for inconsistencies"
fi

echo ""
echo "💡 Quick Commands:"
echo "   ./set-debug-mode.sh     - Enable debug mode"
echo "   ./set-production-mode.sh - Enable production mode"
echo "   ./check-mode.sh         - Check current mode"
