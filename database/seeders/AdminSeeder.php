<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Creates (or resets) the admin login from ADMIN_EMAIL and ADMIN_PASSWORD
 * in .env. Nothing is hard-coded, so each server keeps its own password.
 */
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (! $email || ! $password) {
            $this->command?->warn('Set ADMIN_EMAIL and ADMIN_PASSWORD in .env, then run: php artisan db:seed --class=AdminSeeder');

            return;
        }

        User::updateOrCreate(['email' => $email], [
            'name' => env('ADMIN_NAME', 'Shubhanga Admin'),
            'password' => $password,
            'role' => 'admin',
        ]);

        $this->command?->info("Admin login ready: {$email}");
    }
}
