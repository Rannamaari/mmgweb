<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mmg:create-admin {email=admin@mmg.local} {password=password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or update admin user for MMG system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Admin',
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]
        );

        $this->info("Admin user created/updated successfully!");
        $this->info("Email: {$email}");
        $this->info("Password: {$password}");
        
        return Command::SUCCESS;
    }
}
