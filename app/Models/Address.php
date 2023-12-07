<?php

namespace App\Models;

use App\Enums\RelatedTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'addresses';

    protected $fillable = [
        'related_id',
        'related_type',
        'province',
        'city',
        'address',
        'zip',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'related_type' => RelatedTypeEnum::class,
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    protected $appends = [
        'full_address',
        'full_name',
    ];

    public function related(): MorphTo
    {
        return $this->morphTo();
    }

    public function getFullAddressAttribute(): string
    {
        return "{$this->province} - {$this->city} - {$this->address}";
    }

    public function getFullNameAttribute(): string
    {
        if ($this->related instanceof Customer) {
            return $this->related->full_name;
        }

        return '';
    }

}
