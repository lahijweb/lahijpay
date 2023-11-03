<?php

namespace App\Models;

use App\Enums\InvoicePeopleTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoicePeople extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'invoice_people';

    protected $fillable = [
        'type',
        'name',
        'identity_no',
        'register_no',
        'finance_no',
        'phone',
        'zip',
        'address',
    ];

    protected $casts = [
        'type' => InvoicePeopleTypeEnum::class,
        'name' => 'string',
        'identity_no' => 'string',
        'register_no' => 'string',
        'finance_no' => 'string',
        'phone' => 'string',
        'zip' => 'string',
        'address' => 'string',
    ];

}
