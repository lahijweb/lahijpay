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
        'status' => InvoiceStatusEnum::class,
        'due_at' => 'datetime',
        'paid_at' => 'datetime',
        'canceled_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(InvoiceProduct::class);
    }

}
