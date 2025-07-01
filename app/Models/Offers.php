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
        'client_version_id',
        'number',
        'date',
        'tax_id',
        'condition_id',
        'offer_id',
        'comment',
        'description'
    ];

    /**
     * Beziehung zur Client-Version
     */
    public function clientVersion()
    {
        return $this->belongsTo(Clients::class, 'client_version_id');
    }
}
