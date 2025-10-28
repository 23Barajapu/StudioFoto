<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@photostudio.com',
            'password' => Hash::make('password123'),
            'phone' => '081234567890',
            'address' => 'Jl. Studio Photo No. 123, Jakarta',
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Sample Customers
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('password123'),
            'phone' => '082345678901',
            'address' => 'Jl. Contoh No. 456, Bandung',
            'role' => 'customer',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@example.com',
            'password' => Hash::make('password123'),
            'phone' => '083456789012',
            'address' => 'Jl. Sample No. 789, Surabaya',
            'role' => 'customer',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->command->info('Users created successfully!');
        $this->command->info('Admin: admin@photostudio.com / password123');
        $this->command->info('Customer: budi@example.com / password123');
    }
}
