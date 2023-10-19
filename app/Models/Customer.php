<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customers';

    protected $fillable = [
        'identity_no',
        'register_no',
        'finance_no',
        'first_name',
        'last_name',
        'email',
        'mobile',
        'phone',
        'business_name',
        'additional_Info',
        'is_business',
        'is_active',
    ];

    protected $casts = [
        'is_business' => 'boolean',
        'is_active' => 'boolean',
    ];

}
