<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔐 MMG User Authentication Test\n";
echo "===============================\n\n";

// Test 1: Create a test user
echo "1. Creating Test User:\n";
try {
    $testUser = User::updateOrCreate(
        ['email' => 'testauth@mmg.mv'],
        [
            'name' => 'Auth Test User',
            'password' => 'testpassword123',
            'email_verified_at' => now(),
        ]
    );

    echo "   ✅ User created: {$testUser->name} ({$testUser->email})\n";
    echo "   🔑 Password hash: " . substr($testUser->password, 0, 20) . "...\n";
} catch (Exception $e) {
    echo "   ❌ Error creating user: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n";

// Test 2: Test authentication
echo "2. Testing Authentication:\n";
try {
    $user = User::where('email', 'testauth@mmg.mv')->first();

    // Test correct password
    if (Hash::check('testpassword123', $user->password)) {
        echo "   ✅ Correct password authentication: SUCCESS\n";
    } else {
        echo "   ❌ Correct password authentication: FAILED\n";
    }

    // Test wrong password
    if (!Hash::check('wrongpassword', $user->password)) {
        echo "   ✅ Wrong password rejection: SUCCESS\n";
    } else {
        echo "   ❌ Wrong password rejection: FAILED\n";
    }

} catch (Exception $e) {
    echo "   ❌ Error testing authentication: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 3: Test Filament form simulation
echo "3. Testing Filament Form Simulation:\n";
try {
    // Simulate what happens in Filament form
    $formData = [
        'name' => 'Filament Test User',
        'email' => 'filament@mmg.mv',
        'password' => 'filamentpass123',
        'password_confirmation' => 'filamentpass123',
        'email_verified_at' => now(),
    ];

    // Create user with form data
    $filamentUser = User::create([
        'name' => $formData['name'],
        'email' => $formData['email'],
        'password' => $formData['password'],
        'email_verified_at' => $formData['email_verified_at'],
    ]);

    echo "   ✅ Filament user created: {$filamentUser->name}\n";

    // Test authentication
    if (Hash::check('filamentpass123', $filamentUser->password)) {
        echo "   ✅ Filament user authentication: SUCCESS\n";
    } else {
        echo "   ❌ Filament user authentication: FAILED\n";
    }

} catch (Exception $e) {
    echo "   ❌ Error testing Filament form: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 4: List all test users
echo "4. All Test Users:\n";
$testUsers = User::whereIn('email', ['testauth@mmg.mv', 'filament@mmg.mv', 'test2@mmg.mv'])->get();
foreach ($testUsers as $user) {
    echo "   👤 {$user->name} ({$user->email})\n";
    echo "      Password hash: " . substr($user->password, 0, 20) . "...\n";
    echo "      Can authenticate: " . (Hash::check('testpassword123', $user->password) || Hash::check('filamentpass123', $user->password) || Hash::check('password123', $user->password) ? 'YES' : 'NO') . "\n";
    echo "\n";
}

echo "🎉 User Authentication Test Complete!\n";
echo "If all tests pass, user creation through Filament should work correctly.\n";
echo "Try creating a user through the admin panel and then logging in.\n";
