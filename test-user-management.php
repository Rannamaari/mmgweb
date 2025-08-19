<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Role;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔐 MMG User Management System Test\n";
echo "==================================\n\n";

// Test 1: Check if roles exist
echo "1. Testing Roles:\n";
$roles = Role::all();
foreach ($roles as $role) {
    echo "   ✅ {$role->name} ({$role->slug}) - " . count($role->permissions ?? []) . " permissions\n";
}
echo "\n";

// Test 2: Check admin user
echo "2. Testing Admin User:\n";
$admin = User::where('email', 'admin@mmg.mv')->first();
if ($admin) {
    echo "   ✅ Admin user found: {$admin->name} ({$admin->email})\n";
    echo "   📋 Roles: " . $admin->roles->pluck('name')->implode(', ') . "\n";
    echo "   🔑 Permissions: " . count($admin->getAllPermissions()) . " total\n";
} else {
    echo "   ❌ Admin user not found!\n";
}
echo "\n";

// Test 3: Test permission checking
echo "3. Testing Permission System:\n";
if ($admin) {
    $testPermissions = [
        'admin.access',
        'pos.access',
        'booking.access',
        'admin.users',
        'pos.sales'
    ];
    
    foreach ($testPermissions as $permission) {
        $hasPermission = $admin->hasPermission($permission);
        $status = $hasPermission ? '✅' : '❌';
        echo "   {$status} {$permission}: " . ($hasPermission ? 'YES' : 'NO') . "\n";
    }
}
echo "\n";

// Test 4: Create a test user
echo "4. Creating Test User:\n";
try {
    $testUser = User::updateOrCreate(
        ['email' => 'test@mmg.mv'],
        [
            'name' => 'Test User',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]
    );
    
    // Assign POS Operator role
    $posOperatorRole = Role::where('slug', 'pos-operator')->first();
    if ($posOperatorRole) {
        $testUser->roles()->sync([$posOperatorRole->id]);
        echo "   ✅ Test user created: {$testUser->name} ({$testUser->email})\n";
        echo "   📋 Assigned role: {$posOperatorRole->name}\n";
        echo "   🔑 Permissions: " . count($testUser->getAllPermissions()) . " total\n";
        
        // Test specific permissions
        echo "   🧪 Permission tests:\n";
        echo "      - POS access: " . ($testUser->hasPermission('pos.access') ? '✅' : '❌') . "\n";
        echo "      - Admin access: " . ($testUser->hasPermission('admin.access') ? '✅' : '❌') . "\n";
        echo "      - POS sales: " . ($testUser->hasPermission('pos.sales') ? '✅' : '❌') . "\n";
    } else {
        echo "   ❌ POS Operator role not found!\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error creating test user: " . $e->getMessage() . "\n";
}
echo "\n";

// Test 5: List all users and their roles
echo "5. All Users and Roles:\n";
$users = User::with('roles')->get();
foreach ($users as $user) {
    echo "   👤 {$user->name} ({$user->email})\n";
    echo "      Roles: " . $user->roles->pluck('name')->implode(', ') . "\n";
    echo "      Permissions: " . count($user->getAllPermissions()) . " total\n";
    echo "\n";
}

echo "🎉 User Management System Test Complete!\n";
echo "You can now access the admin panel at: http://localhost/admin\n";
echo "Login with: admin@mmg.mv / password\n";
