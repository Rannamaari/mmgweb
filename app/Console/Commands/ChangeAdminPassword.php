<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ChangeAdminPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:change-password {--email=admin@mmg.mv} {--generate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change admin user password securely';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');
        $generate = $this->option('generate');

        $this->info("🔐 MMG Admin Password Change");
        $this->line("");

        // Find the admin user
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("❌ Admin user with email '{$email}' not found!");
            return 1;
        }

        $this->info("📧 Admin user: {$user->email}");
        $this->line("");

        // Generate or get password
        if ($generate) {
            $password = $this->generateSecurePassword();
            $this->info("🔑 Generated password: {$password}");
            $this->warn("⚠️  IMPORTANT: Save this password securely! It won't be shown again.");

            if (!$this->confirm('Do you want to proceed with this password?')) {
                $this->info("Password change cancelled.");
                return 0;
            }
        } else {
            $password = $this->secret('Enter new password (minimum 12 characters):');

            // Validate password
            $validator = Validator::make(['password' => $password], [
                'password' => [
                    'required',
                    'string',
                    'min:12',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/'
                ]
            ]);

            if ($validator->fails()) {
                $this->error("❌ Password does not meet security requirements:");
                foreach ($validator->errors()->get('password') as $error) {
                    $this->line("   • {$error}");
                }
                $this->line("");
                $this->line("Password must contain:");
                $this->line("   • At least 12 characters");
                $this->line("   • At least one uppercase letter");
                $this->line("   • At least one lowercase letter");
                $this->line("   • At least one number");
                $this->line("   • At least one special character (@$!%*?&)");
                return 1;
            }

            $confirmPassword = $this->secret('Confirm new password:');

            if ($password !== $confirmPassword) {
                $this->error("❌ Passwords do not match!");
                return 1;
            }
        }

        // Update the password
        $user->password = Hash::make($password);
        $user->save();

        $this->info("✅ Admin password changed successfully!");
        $this->line("");
        $this->line("🔐 New login credentials:");
        $this->line("   Email: {$user->email}");
        $this->line("   Password: {$password}");
        $this->line("");

        $this->warn("⚠️  IMPORTANT SECURITY NOTES:");
        $this->line("   • Save this password in a secure password manager");
        $this->line("   • Do not share this password");
        $this->line("   • Consider enabling 2FA for additional security");
        $this->line("   • Change this password regularly (every 90 days)");
        $this->line("");

        $this->info("🔒 Additional Security Recommendations:");
        $this->line("   1. Enable 2FA in your admin panel");
        $this->line("   2. Set up login notifications");
        $this->line("   3. Monitor login attempts");
        $this->line("   4. Use a VPN when accessing admin from public networks");
        $this->line("   5. Regularly review admin access logs");

        return 0;
    }

    /**
     * Generate a secure password
     */
    private function generateSecurePassword(): string
    {
        $length = 16;
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=[]{}|;:,.<>?';

        $password = '';

        // Ensure at least one character from each required category
        $password .= 'A'; // uppercase
        $password .= 'a'; // lowercase
        $password .= '1'; // number
        $password .= '!'; // special

        // Fill the rest randomly
        for ($i = 4; $i < $length; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }

        // Shuffle the password
        return str_shuffle($password);
    }
}
