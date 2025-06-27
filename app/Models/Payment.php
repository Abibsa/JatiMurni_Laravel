<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'customer_name',
        'payment_code',
        'payment_method',
        'payment_date',
        'amount_paid',
        'proof_image',
        'status'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount_paid' => 'decimal:2'
    ];

    /**
     * Get the order that owns the payment.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the status label
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'confirmed' => 'Berhasil',
            'rejected' => 'Ditolak',
            default => 'Menunggu'
        };
    }
} 