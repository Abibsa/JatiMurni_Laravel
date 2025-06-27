<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin login: email = azizabib22@gmail.com, password = abib123
        User::create([
            'name' => 'abib',
            'email' => 'azizabib22@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('abib123'),
            'phone' => '081234567890',
            'address' => 'Jl. Test No. 123',
            'role' => 'admin',
            'status' => 'active',
            'remember_token' => \Str::random(10)
        ]);

        // Pengguna login: email = customer@example.com, password = password
        User::create([
            'name' => 'Customer User',
            'email' => 'customer@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'phone' => '089876543210',
            'address' => 'Jl. Customer No. 456',
            'role' => 'customer',
            'status' => 'active',
            'remember_token' => \Str::random(10)
        ]);
    }
} 