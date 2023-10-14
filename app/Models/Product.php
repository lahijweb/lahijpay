<?php

namespace App\Models;

use App\Enums\ProductStatusEnum;
use App\Enums\ProductTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'sold_qty',
        'type',
        'status',
        'is_active',
        'is_scheduled',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'type' => ProductTypeEnum::class,
        'status' => ProductStatusEnum::class,
        'is_active' => 'boolean',
        'is_scheduled' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeScheduled($query)
    {
        return $query->where('is_scheduled', true);
    }
}
