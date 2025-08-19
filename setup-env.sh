#!/bin/bash

# MMG Secure Environment Setup Script
# This script securely sets up environment variables for deployment

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

print_status() {
    echo -e "${BLUE}📋 $1${NC}"
}

print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

print_status "Setting up secure environment variables for MMG deployment..."

# Default values
DOMAIN_NAME="garage.micronet.mv"
DB_HOST="micronetdb-do-user-24249606-0.d.db.ondigitalocean.com"
DB_PORT="25060"
DB_DATABASE="mmgweb"
DB_USERNAME="doadmin"

# Prompt for domain name
echo -n "Enter your domain name (default: $DOMAIN_NAME): "
read -r input_domain
if [ -n "$input_domain" ]; then
    DOMAIN_NAME="$input_domain"
fi

# Prompt for database password securely
echo -n "Enter your database password: "
read -s DB_PASSWORD
echo ""

if [ -z "$DB_PASSWORD" ]; then
    print_error "Database password is required!"
    exit 1
fi

# Set environment variables
export DOMAIN_NAME="$DOMAIN_NAME"
export DB_HOST="$DB_HOST"
export DB_PORT="$DB_PORT"
export DB_DATABASE="$DB_DATABASE"
export DB_USERNAME="$DB_USERNAME"
export DB_PASSWORD="$DB_PASSWORD"

print_success "Environment variables set successfully!"
echo ""
echo "🌐 Domain: $DOMAIN_NAME"
echo "🗄️ Database: $DB_HOST:$DB_PORT/$DB_DATABASE"
echo "👤 Database User: $DB_USERNAME"
echo "🔑 Database Password: [HIDDEN]"
echo ""

# Test database connection
print_status "Testing database connection..."
if psql "postgresql://$DB_USERNAME:$DB_PASSWORD@$DB_HOST:$DB_PORT/$DB_DATABASE?sslmode=require" -c "SELECT 1;" >/dev/null 2>&1; then
    print_success "Database connection successful!"
else
    print_error "Database connection failed. Please check your credentials."
    exit 1
fi

print_success "Environment setup completed!"
echo ""
echo "🚀 You can now run the deployment script:"
echo "   ./deploy.sh"
echo ""
echo "⚠️  Note: These environment variables are only set for this session."
echo "   To make them permanent, add them to your ~/.bashrc or ~/.zshrc file."
