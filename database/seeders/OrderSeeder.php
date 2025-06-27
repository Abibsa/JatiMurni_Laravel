<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run()
    {
        // Get the first user
        $user = User::first();

        if (!$user) {
            $this->command->info('No user found. Please run UserSeeder first.');
            return;
        }

        // Create some test orders
        Order::create([
            'user_id' => $user->id,
            'total_amount' => 1500000,
            'status' => 'pending',
            'shipping_address' => 'Jl. Contoh No. 123, Surabaya'
        ]);

        Order::create([
            'user_id' => $user->id,
            'total_amount' => 2500000,
            'status' => 'processing',
            'shipping_address' => 'Jl. Test No. 456, Malang'
        ]);

        Order::create([
            'user_id' => $user->id,
            'total_amount' => 3500000,
            'status' => 'completed',
            'shipping_address' => 'Jl. Sample No. 789, Sidoarjo'
        ]);

        $this->command->info('Orders seeded successfully!');
    }
} 