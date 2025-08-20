#!/bin/bash

# MMG Production User Management Deployment Script
# This script sets up all users, roles, and permissions on production server

set -e

echo "🚀 Starting MMG Production User Management Deployment..."
echo "=================================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print status
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

# Function to print success
print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

# Function to print error
print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Function to print warning
print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    print_error "This script must be run from the Laravel project root directory"
    exit 1
fi

print_status "Checking current environment..."
if [ "$APP_ENV" != "production" ]; then
    print_warning "APP_ENV is not set to 'production'. Current: $APP_ENV"
fi

print_status "Running database migrations..."
php artisan migrate --force

print_status "Converting permissions column to JSONB for PostgreSQL compatibility..."
php artisan tinker --execute="
try {
    DB::statement('ALTER TABLE roles ALTER COLUMN permissions TYPE jsonb USING permissions::jsonb');
    echo '✅ Permissions column converted to JSONB successfully!' . PHP_EOL;
} catch(Exception \$e) {
    echo 'ℹ️ Permissions column already JSONB or conversion not needed: ' . \$e->getMessage() . PHP_EOL;
}
"

print_status "Creating Super Admin role..."
php artisan tinker --execute="
try {
    \$superAdmin = DB::insert('INSERT INTO roles (name, slug, description, permissions, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)', [
        'Super Admin', 
        'super-admin', 
        'Full access to all features', 
        json_encode([
            'admin.access', 'admin.dashboard', 'admin.users', 'admin.roles', 'admin.products', 
            'admin.customers', 'admin.motorcycles', 'admin.invoices', 'admin.inventory', 
            'admin.bookings', 'admin.reports', 'admin.settings', 'pos.access', 'pos.sales', 
            'pos.refunds', 'pos.customers', 'pos.products', 'pos.reports', 'booking.access', 
            'booking.create', 'booking.edit', 'booking.delete', 'booking.view', 
            'profile.edit', 'profile.view'
        ]), 
        'true', 
        now(), 
        now()
    ]);
    echo '✅ Super Admin role created successfully!' . PHP_EOL;
} catch(Exception \$e) {
    echo 'ℹ️ Super Admin role already exists: ' . \$e->getMessage() . PHP_EOL;
}
"

print_status "Creating POS Manager role..."
php artisan tinker --execute="
try {
    \$posManager = DB::insert('INSERT INTO roles (name, slug, description, permissions, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)', [
        'POS Manager', 
        'pos-manager', 
        'Access to POS system and basic admin features', 
        json_encode([
            'admin.access', 'pos.access', 'pos.sales', 'pos.refunds', 'pos.customers', 
            'pos.products', 'pos.reports', 'admin.products', 'admin.customers', 
            'admin.invoices', 'admin.reports', 'booking.access', 'booking.create', 
            'booking.edit', 'booking.view', 'profile.edit', 'profile.view'
        ]), 
        'true', 
        now(), 
        now()
    ]);
    echo '✅ POS Manager role created successfully!' . PHP_EOL;
} catch(Exception \$e) {
    echo 'ℹ️ POS Manager role already exists: ' . \$e->getMessage() . PHP_EOL;
}
"

print_status "Creating POS Operator role..."
php artisan tinker --execute="
try {
    \$posOperator = DB::insert('INSERT INTO roles (name, slug, description, permissions, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)', [
        'POS Operator', 
        'pos-operator', 
        'Basic POS operations', 
        json_encode([
            'admin.access', 'pos.access', 'pos.sales', 'pos.customers', 'pos.products', 
            'booking.access', 'booking.create', 'booking.view', 'profile.edit', 'profile.view'
        ]), 
        'true', 
        now(), 
        now()
    ]);
    echo '✅ POS Operator role created successfully!' . PHP_EOL;
} catch(Exception \$e) {
    echo 'ℹ️ POS Operator role already exists: ' . \$e->getMessage() . PHP_EOL;
}
"

print_status "Creating Booking Manager role..."
php artisan tinker --execute="
try {
    \$bookingManager = DB::insert('INSERT INTO roles (name, slug, description, permissions, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)', [
        'Booking Manager', 
        'booking-manager', 
        'Manage bookings and customer service', 
        json_encode([
            'admin.access', 'admin.bookings', 'booking.access', 'booking.create', 
            'booking.edit', 'booking.delete', 'booking.view', 'admin.customers', 
            'profile.edit', 'profile.view'
        ]), 
        'true', 
        now(), 
        now()
    ]);
    echo '✅ Booking Manager role created successfully!' . PHP_EOL;
} catch(Exception \$e) {
    echo 'ℹ️ Booking Manager role already exists: ' . \$e->getMessage() . PHP_EOL;
}
"

print_status "Creating production users..."

print_status "Creating/updating Super Admin user..."
php artisan tinker --execute="
\$user = \App\Models\User::updateOrCreate(
    ['email' => 'admin@mmg.mv'],
    [
        'name' => 'Super Admin',
        'password' => bcrypt('Admin@2024!'),
        'email_verified_at' => now()
    ]
);
\$role = \App\Models\Role::where('slug', 'super-admin')->first();
if(\$role) {
    \$user->roles()->sync([\$role->id]);
    echo '✅ Super Admin user created/updated successfully!' . PHP_EOL;
} else {
    echo '❌ Super Admin role not found!' . PHP_EOL;
}
"

print_status "Creating POS Manager user..."
php artisan tinker --execute="
\$user = \App\Models\User::updateOrCreate(
    ['email' => 'posmanager@mmg.mv'],
    [
        'name' => 'POS Manager',
        'password' => bcrypt('PosManager@2024!'),
        'email_verified_at' => now()
    ]
);
\$role = \App\Models\Role::where('slug', 'pos-manager')->first();
if(\$role) {
    \$user->roles()->sync([\$role->id]);
    echo '✅ POS Manager user created/updated successfully!' . PHP_EOL;
} else {
    echo '❌ POS Manager role not found!' . PHP_EOL;
}
"

print_status "Creating POS Operator user..."
php artisan tinker --execute="
\$user = \App\Models\User::updateOrCreate(
    ['email' => 'posoperator@mmg.mv'],
    [
        'name' => 'POS Operator',
        'password' => bcrypt('PosOperator@2024!'),
        'email_verified_at' => now()
    ]
);
\$role = \App\Models\Role::where('slug', 'pos-operator')->first();
if(\$role) {
    \$user->roles()->sync([\$role->id]);
    echo '✅ POS Operator user created/updated successfully!' . PHP_EOL;
} else {
    echo '❌ POS Operator role not found!' . PHP_EOL;
}
"

print_status "Creating Booking Manager user..."
php artisan tinker --execute="
\$user = \App\Models\User::updateOrCreate(
    ['email' => 'bookingmanager@mmg.mv'],
    [
        'name' => 'Booking Manager',
        'password' => bcrypt('BookingManager@2024!'),
        'email_verified_at' => now()
    ]
);
\$role = \App\Models\Role::where('slug', 'booking-manager')->first();
if(\$role) {
    \$user->roles()->sync([\$role->id]);
    echo '✅ Booking Manager user created/updated successfully!' . PHP_EOL;
} else {
    echo '❌ Booking Manager role not found!' . PHP_EOL;
}
"

print_status "Clearing application cache..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear

print_status "Verifying setup..."
php artisan tinker --execute="
echo '=== PRODUCTION USER MANAGEMENT SETUP VERIFICATION ===' . PHP_EOL;
echo 'Roles created: ' . \App\Models\Role::count() . PHP_EOL;
echo 'Users created: ' . \App\Models\User::count() . PHP_EOL;
echo PHP_EOL . '=== USERS AND THEIR ROLES ===' . PHP_EOL;
\$users = \App\Models\User::all();
\$users->each(function(\$user) {
    echo '- ' . \$user->name . ' (' . \$user->email . '): ';
    \$user->roles->each(function(\$role) {
        echo \$role->name . ' ';
    });
    echo PHP_EOL;
});
echo PHP_EOL . '=== PERMISSION TEST ===' . PHP_EOL;
\$posOperator = \App\Models\User::where('email', 'posoperator@mmg.mv')->first();
if(\$posOperator) {
    echo 'POS Operator can access admin: ' . (\$posOperator->hasPermission('admin.access') ? 'YES' : 'NO') . PHP_EOL;
    echo 'POS Operator can manage users: ' . (\$posOperator->hasPermission('admin.users') ? 'YES' : 'NO') . PHP_EOL;
    echo 'POS Operator can access POS: ' . (\$posOperator->hasPermission('pos.access') ? 'YES' : 'NO') . PHP_EOL;
} else {
    echo 'POS Operator user not found!' . PHP_EOL;
}
"

print_success "🎉 Production User Management Deployment Complete!"
echo ""
echo "📋 PRODUCTION LOGIN CREDENTIALS:"
echo "================================="
echo "🔐 Super Admin:     admin@mmg.mv / Admin@2024!"
echo "🔐 POS Manager:     posmanager@mmg.mv / PosManager@2024!"
echo "🔐 POS Operator:    posoperator@mmg.mv / PosOperator@2024!"
echo "🔐 Booking Manager: bookingmanager@mmg.mv / BookingManager@2024!"
echo ""
echo "🌐 Admin Panel: https://garage.micronet.mv/admin"
echo "🌐 POS System:  https://garage.micronet.mv/pos"
echo ""
print_warning "⚠️  IMPORTANT: Change these passwords after first login!"
print_warning "⚠️  These are production credentials - keep them secure!"
