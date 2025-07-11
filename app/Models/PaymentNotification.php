<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentNotification extends Model
{
    protected $table = 'payment_notifications';
    
    protected $fillable = [
        'order_id',
        'transaction_id',
        'transaction_status',
        'payment_type',
        'gross_amount',
        'currency',
        'fraud_status',
        'transaction_time',
        'settlement_time',
        'raw_payload',
    ];

    protected $casts = [
        'transaction_time' => 'datetime',
        'settlement_time' => 'datetime',
        'raw_payload' => 'array',
    ];
}
