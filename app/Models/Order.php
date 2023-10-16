<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'product_id',
        'payment_id',
        'qty',
        'total_price',
        'first_name',
        'last_name',
        'email',
        'mobile',
        'province',
        'city',
        'address',
        'zip',
        'status_id',
        'tracking_code',
        'note',
    ];

    protected $casts = [
        'qty' => 'integer',
        'total_price' => 'decimal:4',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class);
    }


}
