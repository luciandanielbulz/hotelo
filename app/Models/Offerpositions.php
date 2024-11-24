<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offerpositions extends Model
{
    use HasFactory;
    protected $fillable = [
        'offer_id',  // Neu hinzugefügt
        'amount',
        'designation',
        'details',
        'unit_id',
        'price',
        'positiontext',
        'sequence'

        // Weitere Spalten hinzufügen, falls nötig
    ];
}
