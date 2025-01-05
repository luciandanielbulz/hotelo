<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    public function invoicePositions()
    {
        return $this->hasMany(InvoicePositions::class, 'invoice_id');
    }



    protected $casts = [
        'date' => 'datetime',
    ];
    protected $fillable = [
        'customer_id',
        'number',
        'tax_id',
        'condition_id',
        'invoice_id',
        'number',

    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function positions()
    {
        return $this->hasMany(Invoicepositions::class);
    }

    public function getTotalPriceAttribute()
    {
        return $this->positions->sum(function ($position) {
            return $position->amount * $position->price;
        });
    }
}
