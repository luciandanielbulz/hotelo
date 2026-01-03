<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_de',
        'iso_code',
        'iso_code_3',
        'phone_code',
        'currency_code',
        'is_eu_member',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_eu_member' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope für aktive Länder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope für EU-Mitglieder
     */
    public function scopeEuMembers($query)
    {
        return $query->where('is_eu_member', true);
    }

    /**
     * Scope für sortierte Länder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
