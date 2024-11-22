<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoicepositions extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_id',  // Neu hinzugefügt
        'amount',
        'designation',
        'details',
        'unit_id',
        'price',
        'positiontext'

        // Weitere Spalten hinzufügen, falls nötig
    ];
}
