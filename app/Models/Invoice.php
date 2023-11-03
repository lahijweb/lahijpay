<?php

namespace App\Models;

use App\Enums\InvoiceStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'uuid',
        'invoice_no',
        'customer_id',
        'amount',
        'discount',
        'tax',
        'shipping',
        'total',
        'status',
        'due_at',
        'paid_at',
        'canceled_at',
    ];

    protected $casts = [
        'uuid' => 'string',
        'invoice_no' => 'string',
        'customer_id' => 'integer',
        'amount' => 'float',
        'discount' => 'float',
        'tax' => 'float',
        'shipping' => 'float',
        'total' => 'float',
        'status' => InvoiceStatusEnum::class,
        'due_at' => 'datetime',
        'paid_at' => 'datetime',
        'canceled_at' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(InvoiceProduct::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(InvoicePeople::class, 'seller_id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(InvoicePeople::class, 'buyer_id');
    }

}
