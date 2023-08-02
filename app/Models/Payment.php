<?php

namespace App\Models;

use App\Enums\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'uuid', 'gateway_id', 'amount', 'first_name', 'last_name', 'email', 'mobile', 'description', 'status', 'transactionid', 'referenceid', 'verified_at'
    ];

    protected $casts = [
        'first_name' => 'encrypted',
        'last_name' => 'encrypted',
        'email' => 'encrypted',
        'mobile' => 'encrypted',
        'status' => PaymentStatusEnum::class,
    ];
}
