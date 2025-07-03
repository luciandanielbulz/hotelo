<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name', 
        'symbol',
        'exchange_rate',
        'is_default',
        'client_id',
    ];

    protected $casts = [
        'exchange_rate' => 'decimal:4',
        'is_default' => 'boolean',
    ];

    /**
     * Beziehung zum Client
     */
    public function client()
    {
        return $this->belongsTo(Clients::class);
    }

    /**
     * Scope für aktive Standard-Währung eines Clients
     */
    public function scopeDefaultForClient($query, $clientId)
    {
        return $query->where('client_id', $clientId)->where('is_default', true);
    }

    /**
     * Setzt diese Währung als Standard für den Client
     */
    public function setAsDefault()
    {
        // Alle anderen Währungen des Clients auf nicht-Standard setzen
        self::where('client_id', $this->client_id)
            ->where('id', '!=', $this->id)
            ->update(['is_default' => false]);
            
        // Diese Währung als Standard setzen
        $this->update(['is_default' => true]);
    }
}
