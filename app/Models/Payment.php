<?php

namespace App\Models;

use App\Enums\PayableTypeEnum;
use App\Enums\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'uuid', 'gateway_id', 'amount', 'first_name', 'last_name', 'email', 'mobile', 'description', 'status', 'transactionid', 'referenceid', 'verified_at', 'payable_type', 'payable_id'
    ];

    protected $casts = [
        'payable_type' => PayableTypeEnum::class,
        'status' => PaymentStatusEnum::class,
    ];

    public function gateway(): BelongsTo
    {
        return $this->belongsTo(Gateway::class, 'gateway_id');
    }

    public function payable()
    {
        return $this->morphTo()->withTrashed();
    }
}
