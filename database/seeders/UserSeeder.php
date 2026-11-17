<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun Owner Default
        User::updateOrCreate(
            ['email' => 'owner@coffeeshop.com'],
            [
                'name' => 'Owner CoffeeShop',
                'phone' => '081234567890',
                'password' => Hash::make('password123'),
                'role' => 'owner',
                'is_active' => true,
            ]
        );

        // 2. Akun Worker / Kasir Default
        User::updateOrCreate(
            ['email' => 'worker@coffeeshop.com'],
            [
                'name' => 'Worker Kasir',
                'phone' => '089876543210',
                'password' => Hash::make('password123'),
                'role' => 'worker',
                'is_active' => true,
            ]
        );
    }
}