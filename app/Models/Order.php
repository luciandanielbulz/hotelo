<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'company',
        'phone',
        'street',
        'postal_code',
        'city',
        'country',
        'uid_number',
        'plan',
        'is_kleinunternehmer',
        'message',
        'recaptcha_token',
        'ip_address',
        'status',
    ];

    protected $casts = [
        'is_kleinunternehmer' => 'boolean',
    ];
}
