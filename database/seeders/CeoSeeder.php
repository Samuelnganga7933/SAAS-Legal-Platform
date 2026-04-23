<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class CeoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the platform owner (CEO) by email
        // If you have a specific email, update this condition
        $ceoEmail = env('CEO_EMAIL', 'admin@lenium.legal');
        
        $ceo = User::where('email', $ceoEmail)->first();
        
        if ($ceo) {
            $ceo->update([
                'is_ceo' => true,
                'type' => 'worker',
                'role_id' => null,
            ]);
            $this->command->info("CEO set: {$ceo->name} ({$ceo->email})");
        } else {
            $this->command->warn("No user found with email: {$ceoEmail}");
            $this->command->info("Update CEO_EMAIL in .env to set the CEO");
        }
    }
}
