<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'invoice_products';

    protected $fillable = [
        'invoice_id',
        'product_id',
        'sku',
        'title',
        'qty',
        'price',
        'discount',
        'tax',
        'total',
    ];

    protected $casts = [
        'invoice_id' => 'integer',
        'product_id' => 'integer',
        'sku' => 'string',
        'title' => 'string',
        'qty' => 'integer',
        'price' => 'float',
        'discount' => 'float',
        'tax' => 'float',
        'total' => 'float',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
