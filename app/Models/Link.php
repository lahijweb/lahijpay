<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $table = 'links';

    protected $fillable = [
        'slug',
        'title',
        'description',
        'is_active',
        'max_uses',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'max_uses' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
}
