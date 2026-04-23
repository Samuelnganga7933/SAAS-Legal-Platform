<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'leumasnjogu@gmail.com'],
            [
                'name' => 'Admin Portal',
                'password' => Hash::make('1234567890#Samuel'),
                'is_admin' => true,
                'is_verified_client' => true,
                'account_type' => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }
}
