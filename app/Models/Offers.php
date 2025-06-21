<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Offers extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'datetime',
    ];
    protected $fillable = [
        'customer_id',
        'number',
        'date',
        'tax_id',
        'condition_id',
        'offer_id',
        'comment',
        'description'
    ];
}
