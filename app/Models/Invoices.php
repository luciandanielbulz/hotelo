<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'number',
        'tax_id',
        'condition_id',
        'number'
    ];
}
