<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Offers extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'number',
        'tax_id',
        'condition_id',
        'offer_id',
        'number'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function condition()
    {
        return $this->belongsTo(Conditions::class, 'condition_id', 'id');
    }

    public function taxrate()
    {
        return $this->belongsTo(Taxrates::class, 'tax_id', 'id');
    }


}
