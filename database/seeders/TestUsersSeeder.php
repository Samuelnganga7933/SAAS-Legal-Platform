<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::updateOrCreate(
            ['email' => 'samuelnganga7933@gmail.com'],
            [
                'name' => 'Samuel Admin',
                'email' => 'samuelnganga7933@gmail.com',
                'password' => Hash::make('1234567890'),
                'role' => 'admin',
                'is_admin' => true,
                'account_type' => 'b2b',
                'email_verified' => true,
                'email_verified_at' => now(),
            ]
        );

        // Employee/Worker User
        User::updateOrCreate(
            ['email' => 'testemployee1@gmail.com'],
            [
                'name' => 'Test Employee',
                'email' => 'testemployee1@gmail.com',
                'password' => Hash::make('1234567890'),
                'role' => 'worker',
                'is_admin' => false,
                'account_type' => 'b2b',
                'email_verified' => true,
                'email_verified_at' => now(),
            ]
        );

        // Client User
        User::updateOrCreate(
            ['email' => 'testclient1@gmail.com'],
            [
                'name' => 'Test Client',
                'email' => 'testclient1@gmail.com',
                'password' => Hash::make('1234567890'),
                'role' => 'client',
                'is_admin' => false,
                'account_type' => 'b2c',
                'email_verified' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
