<?php

namespace App\Models;

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

    public function payment(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
