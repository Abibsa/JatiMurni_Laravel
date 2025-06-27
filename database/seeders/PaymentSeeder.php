<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        Payment::create([
            'payment_code' => 'P001',
            'payment_date' => Carbon::now()->subDays(2),
            'customer_name' => 'Abib',
            'payment_method' => 'Transfer Bank',
            'amount_paid' => 1500000,
            'status' => 'confirmed',
            'proof_image' => null,
        ]);
        Payment::create([
            'payment_code' => 'P002',
            'payment_date' => Carbon::now()->subDay(),
            'customer_name' => 'Customer User',
            'payment_method' => 'E-Wallet',
            'amount_paid' => 2000000,
            'status' => 'pending',
            'proof_image' => null,
        ]);
    }
} 