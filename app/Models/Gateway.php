<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gateway extends Model
{
    use HasFactory;

    protected $table = 'gateways';

    protected $fillable = [
        'driver', 'name', 'config', 'is_default', 'is_active'
    ];

    protected $casts = [
        'config' => 'encrypted:array',
        'is_default' => 'boolean',
        'is_active' => 'boolean'
    ];

    protected static function booted(): void
    {
        static::updated(function ($gateway) {
            if ($gateway->isDirty('is_default')) {
                static::withoutId($gateway->id)->update(['is_default' => false]);
            }
        });
    }

    public function payment(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function scopeWithoutId(Builder $query, int $id): void
    {
        $query->where('id', '<>', $id);
    }
}
