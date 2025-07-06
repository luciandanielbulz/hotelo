<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'color',
        'is_active',
        'client_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Beziehung zum Client
     */
    public function client()
    {
        return $this->belongsTo(Clients::class);
    }

    /**
     * Beziehung zu InvoiceUploads
     */
    public function invoiceUploads()
    {
        return $this->hasMany(InvoiceUpload::class);
    }

    /**
     * Scope für aktive Kategorien
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope für einen bestimmten Client
     */
    public function scopeForClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }
}
