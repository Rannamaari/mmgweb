#!/bin/bash

# MMG Production Server Fix Script
# Fixes user management, role permissions, and creates mechanic users

set -e

echo "🔧 Starting MMG Production Server Fix..."
echo "========================================"

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

print_status "Creating/updating all roles with correct permissions..."

# Super Admin Role
php artisan tinker --execute="
try {
    DB::table('roles')->updateOrInsert(
        ['slug' => 'super-admin'],
        [
            'name' => 'Super Admin',
            'description' => 'Full access to all features',
            'permissions' => json_encode([
                'admin.access', 'admin.dashboard', 'admin.users', 'admin.roles', 'admin.products', 
                'admin.customers', 'admin.motorcycles', 'admin.invoices', 'admin.inventory', 
                'admin.bookings', 'admin.reports', 'admin.settings', 'pos.access', 'pos.sales', 
                'pos.refunds', 'pos.customers', 'pos.products', 'pos.reports', 'booking.access', 
                'booking.create', 'booking.edit', 'booking.delete', 'booking.view', 
                'profile.edit', 'profile.view'
            ]),
            'is_active' => 'true',
            'updated_at' => now()
        ]
    );
    echo '✅ Super Admin role updated successfully!' . PHP_EOL;
} catch(Exception \$e) {
    echo '❌ Error updating Super Admin role: ' . \$e->getMessage() . PHP_EOL;
}
"

# POS Manager Role
php artisan tinker --execute="
try {
    DB::table('roles')->updateOrInsert(
        ['slug' => 'pos-manager'],
        [
            'name' => 'POS Manager',
            'description' => 'Access to POS system and basic admin features',
            'permissions' => json_encode([
                'admin.access', 'pos.access', 'pos.sales', 'pos.refunds', 'pos.customers', 
                'pos.products', 'pos.reports', 'admin.products', 'admin.customers', 
                'admin.invoices', 'admin.reports', 'booking.access', 'booking.create', 
                'booking.edit', 'booking.view', 'profile.edit', 'profile.view'
            ]),
            'is_active' => 'true',
            'updated_at' => now()
        ]
    );
    echo '✅ POS Manager role updated successfully!' . PHP_EOL;
} catch(Exception \$e) {
    echo '❌ Error updating POS Manager role: ' . \$e->getMessage() . PHP_EOL;
}
"

# POS Operator Role
php artisan tinker --execute="
try {
    DB::table('roles')->updateOrInsert(
        ['slug' => 'pos-operator'],
        [
            'name' => 'POS Operator',
            'description' => 'Basic POS operations',
            'permissions' => json_encode([
                'admin.access', 'pos.access', 'pos.sales', 'pos.customers', 'pos.products', 
                'booking.access', 'booking.create', 'booking.view', 'profile.edit', 'profile.view'
            ]),
            'is_active' => 'true',
            'updated_at' => now()
        ]
    );
    echo '✅ POS Operator role updated successfully!' . PHP_EOL;
} catch(Exception \$e) {
    echo '❌ Error updating POS Operator role: ' . \$e->getMessage() . PHP_EOL;
}
"

# Booking Manager Role
php artisan tinker --execute="
try {
    DB::table('roles')->updateOrInsert(
        ['slug' => 'booking-manager'],
        [
            'name' => 'Booking Manager',
            'description' => 'Manage bookings and customer service',
            'permissions' => json_encode([
                'admin.access', 'admin.bookings', 'booking.access', 'booking.create', 
                'booking.edit', 'booking.delete', 'booking.view', 'admin.customers', 
                'profile.edit', 'profile.view'
            ]),
            'is_active' => 'true',
            'updated_at' => now()
        ]
    );
    echo '✅ Booking Manager role updated successfully!' . PHP_EOL;
} catch(Exception \$e) {
    echo '❌ Error updating Booking Manager role: ' . \$e->getMessage() . PHP_EOL;
}
"

# Mechanic Role
php artisan tinker --execute="
try {
    DB::table('roles')->updateOrInsert(
        ['slug' => 'mechanic'],
        [
            'name' => 'Mechanic',
            'description' => 'Mechanic access to POS and basic features',
            'permissions' => json_encode([
                'admin.access', 'pos.access', 'pos.sales', 'pos.customers', 'pos.products', 
                'admin.products', 'admin.customers', 'booking.access', 'booking.create', 
                'booking.view', 'profile.edit', 'profile.view'
            ]),
            'is_active' => 'true',
            'updated_at' => now()
        ]
    );
    echo '✅ Mechanic role created successfully!' . PHP_EOL;
} catch(Exception \$e) {
    echo '❌ Error creating Mechanic role: ' . \$e->getMessage() . PHP_EOL;
}
"

print_status "Creating/updating all users with correct passwords..."

# Super Admin
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
    echo '✅ Super Admin user updated successfully!' . PHP_EOL;
} else {
    echo '❌ Super Admin role not found!' . PHP_EOL;
}
"

# POS Manager
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
    echo '✅ POS Manager user updated successfully!' . PHP_EOL;
} else {
    echo '❌ POS Manager role not found!' . PHP_EOL;
}
"

# POS Operator
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
    echo '✅ POS Operator user updated successfully!' . PHP_EOL;
} else {
    echo '❌ POS Operator role not found!' . PHP_EOL;
}
"

# Booking Manager
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
    echo '✅ Booking Manager user updated successfully!' . PHP_EOL;
} else {
    echo '❌ Booking Manager role not found!' . PHP_EOL;
}
"

# Mechanic M1
php artisan tinker --execute="
\$user = \App\Models\User::updateOrCreate(
    ['email' => 'm1@mmg.mv'],
    [
        'name' => 'Mechanic M1',
        'password' => bcrypt('MechanicM1@2024!'),
        'email_verified_at' => now()
    ]
);
\$role = \App\Models\Role::where('slug', 'mechanic')->first();
if(\$role) {
    \$user->roles()->sync([\$role->id]);
    echo '✅ Mechanic M1 user created successfully!' . PHP_EOL;
} else {
    echo '❌ Mechanic role not found!' . PHP_EOL;
}
"

# Mechanic M2
php artisan tinker --execute="
\$user = \App\Models\User::updateOrCreate(
    ['email' => 'm2@mmg.mv'],
    [
        'name' => 'Mechanic M2',
        'password' => bcrypt('MechanicM2@2024!'),
        'email_verified_at' => now()
    ]
);
\$role = \App\Models\Role::where('slug', 'mechanic')->first();
if(\$role) {
    \$user->roles()->sync([\$role->id]);
    echo '✅ Mechanic M2 user created successfully!' . PHP_EOL;
} else {
    echo '❌ Mechanic role not found!' . PHP_EOL;
}
"

print_status "Clearing application cache..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear

print_status "Verifying setup..."
php artisan tinker --execute="
echo '=== PRODUCTION SERVER FIX VERIFICATION ===' . PHP_EOL;
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
echo PHP_EOL . '=== PASSWORD TEST ===' . PHP_EOL;
\$admin = \App\Models\User::where('email', 'admin@mmg.mv')->first();
if(\$admin) {
    if(Hash::check('Admin@2024!', \$admin->password)) {
        echo '✅ Admin password working correctly!' . PHP_EOL;
    } else {
        echo '❌ Admin password not working!' . PHP_EOL;
    }
} else {
    echo '❌ Admin user not found!' . PHP_EOL;
}
echo PHP_EOL . '=== PERMISSION TEST ===' . PHP_EOL;
\$admin = \App\Models\User::where('email', 'admin@mmg.mv')->first();
if(\$admin) {
    echo 'Admin can access users: ' . (\$admin->hasPermission('admin.users') ? 'YES' : 'NO') . PHP_EOL;
    echo 'Admin can access roles: ' . (\$admin->hasPermission('admin.roles') ? 'YES' : 'NO') . PHP_EOL;
    echo 'Admin can access admin panel: ' . (\$admin->hasPermission('admin.access') ? 'YES' : 'NO') . PHP_EOL;
} else {
    echo 'Admin user not found!' . PHP_EOL;
}
"

print_success "🎉 Production Server Fix Complete!"
echo ""
echo "📋 UPDATED PRODUCTION LOGIN CREDENTIALS:"
echo "========================================"
echo "🔐 Super Admin:     admin@mmg.mv / Admin@2024!"
echo "🔐 POS Manager:     posmanager@mmg.mv / PosManager@2024!"
echo "🔐 POS Operator:    posoperator@mmg.mv / PosOperator@2024!"
echo "🔐 Booking Manager: bookingmanager@mmg.mv / BookingManager@2024!"
echo "🔐 Mechanic M1:     m1@mmg.mv / MechanicM1@2024!"
echo "🔐 Mechanic M2:     m2@mmg.mv / MechanicM2@2024!"
echo ""
echo "🌐 Admin Panel: https://garage.micronet.mv/admin"
echo "🌐 POS System:  https://garage.micronet.mv/pos"
echo ""
print_warning "⚠️  IMPORTANT: Change these passwords after first login!"
print_warning "⚠️  These are production credentials - keep them secure!"
