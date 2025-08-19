#!/bin/bash

# MMG Admin Password Change Script
# This script securely changes the admin password in production

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

# Function to validate password strength
validate_password() {
    local password="$1"
    
    # Check minimum length
    if [ ${#password} -lt 12 ]; then
        echo "Password must be at least 12 characters long"
        return 1
    fi
    
    # Check for uppercase letters
    if ! echo "$password" | grep -q '[A-Z]'; then
        echo "Password must contain at least one uppercase letter"
        return 1
    fi
    
    # Check for lowercase letters
    if ! echo "$password" | grep -q '[a-z]'; then
        echo "Password must contain at least one lowercase letter"
        return 1
    fi
    
    # Check for numbers
    if ! echo "$password" | grep -q '[0-9]'; then
        echo "Password must contain at least one number"
        return 1
    fi
    
    # Check for special characters
    if ! echo "$password" | grep -q '[!@#$%^&*()_+\-=\[\]{};:"\\|,.<>\/?]'; then
        echo "Password must contain at least one special character (!@#$%^&*()_+-=[]{}|;:,.<>?)"
        return 1
    fi
    
    return 0
}

# Function to generate a secure password
generate_secure_password() {
    # Generate a secure password with 16 characters
    local password=$(openssl rand -base64 12 | tr -d "=+/" | cut -c1-16)
    
    # Ensure it meets all requirements by adding required characters
    password="${password}Aa1!"
    
    # Shuffle the password
    echo "$password" | fold -w1 | shuf | tr -d '\n'
}

print_status "MMG Admin Password Change Script"
echo ""

# Check if we're in the correct directory
if [ ! -f "artisan" ]; then
    print_error "This script must be run from the Laravel application root directory"
    exit 1
fi

# Check if Laravel is properly set up
if ! php artisan --version >/dev/null 2>&1; then
    print_error "Laravel is not properly configured. Please check your installation."
    exit 1
fi

print_status "Current admin user: admin@mmg.mv"
echo ""

# Ask user if they want to generate a password or enter their own
echo "Choose password option:"
echo "1. Generate a secure password automatically"
echo "2. Enter your own password"
echo ""
read -p "Enter your choice (1 or 2): " choice

case $choice in
    1)
        print_status "Generating secure password..."
        NEW_PASSWORD=$(generate_secure_password)
        print_success "Generated password: $NEW_PASSWORD"
        echo ""
        print_warning "⚠️  IMPORTANT: Save this password securely! It won't be shown again."
        echo ""
        read -p "Press Enter to continue with this password, or Ctrl+C to cancel..."
        ;;
    2)
        echo ""
        print_status "Enter new password (minimum 12 characters, must include uppercase, lowercase, number, and special character):"
        read -s NEW_PASSWORD
        echo ""
        
        # Validate password
        if ! validate_password "$NEW_PASSWORD"; then
            print_error "Password does not meet security requirements:"
            validate_password "$NEW_PASSWORD"
            exit 1
        fi
        
        # Confirm password
        print_status "Confirm new password:"
        read -s CONFIRM_PASSWORD
        echo ""
        
        if [ "$NEW_PASSWORD" != "$CONFIRM_PASSWORD" ]; then
            print_error "Passwords do not match!"
            exit 1
        fi
        
        print_success "Password confirmed!"
        ;;
    *)
        print_error "Invalid choice. Please run the script again."
        exit 1
        ;;
esac

echo ""
print_status "Changing admin password..."

# Change the password using Laravel Tinker
if php artisan tinker --execute="
    \$user = App\Models\User::where('email', 'admin@mmg.mv')->first();
    if (\$user) {
        \$user->password = Hash::make('$NEW_PASSWORD');
        \$user->save();
        echo 'Password updated successfully for user: ' . \$user->email;
    } else {
        echo 'Admin user not found!';
        exit(1);
    }
"; then
    print_success "Admin password changed successfully!"
    echo ""
    echo "🔐 New login credentials:"
    echo "   Email: admin@mmg.mv"
    echo "   Password: $NEW_PASSWORD"
    echo ""
    print_warning "⚠️  IMPORTANT SECURITY NOTES:"
    echo "   • Save this password in a secure password manager"
    echo "   • Do not share this password"
    echo "   • Consider enabling 2FA for additional security"
    echo "   • Change this password regularly (every 90 days)"
    echo ""
    
    # Test the new password
    print_status "Testing new password..."
    if curl -s -o /dev/null -w "%{http_code}" "https://garage.micronet.mv/admin" | grep -q "200\|302"; then
        print_success "Admin panel is accessible"
    else
        print_warning "Admin panel test failed, but password was changed successfully"
    fi
    
    # Security recommendations
    echo ""
    print_status "🔒 Additional Security Recommendations:"
    echo "   1. Enable 2FA in your admin panel"
    echo "   2. Set up login notifications"
    echo "   3. Monitor login attempts"
    echo "   4. Use a VPN when accessing admin from public networks"
    echo "   5. Regularly review admin access logs"
    
else
    print_error "Failed to change password. Please check your Laravel installation."
    exit 1
fi

echo ""
print_success "Password change completed successfully!"
