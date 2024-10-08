<?php

namespace App\Models;

use App\Enums\ProductStatusEnum;
use App\Enums\ProductTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sku',
        'slug',
        'title',
        'description',
        'price',
        'qty',
        'type',
        'status',
        'is_active',
        'get_address',
        'is_scheduled',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'type' => ProductTypeEnum::class,
        'status' => ProductStatusEnum::class,
        'is_active' => 'boolean',
        'get_address' => 'boolean',
        'is_scheduled' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeScheduled($query)
    {
        return $query->where('is_scheduled', true);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'product_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'product_id');
    }

    public function isProductAvailable()
    {
        return $this->qty > 0;
    }

    public function isProductUnlimited()
    {
        return $this->qty === null;
    }

    public function isProductForSale()
    {
        return $this->isProductAvailable() || $this->isProductUnlimited();
    }

}
