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
        'keywords',
        'color',
        'is_active',
        'client_id',
        'billing_duration_years',
        'percentage',
        'type',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'billing_duration_years' => 'integer',
        'percentage' => 'decimal:2',
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
     * Beziehung zu BankData
     */
    public function bankData()
    {
        return $this->hasMany(BankData::class);
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

    /**
     * Get keywords as array
     */
    public function getKeywordsArrayAttribute()
    {
        if (empty($this->keywords)) {
            return [];
        }
        return array_map('trim', explode(',', $this->keywords));
    }

    /**
     * Set keywords from array
     */
    public function setKeywordsArrayAttribute($keywords)
    {
        $this->attributes['keywords'] = is_array($keywords) ? implode(', ', $keywords) : $keywords;
    }

    /**
     * Check if this category matches a transaction
     */
    public function matchesTransaction($transaction)
    {
        if (empty($this->keywords)) {
            return false;
        }

        $keywords = $this->getKeywordsArrayAttribute();
        $searchText = strtolower($transaction->partnername . ' ' . $transaction->reference . ' ' . $transaction->partneriban);

        foreach ($keywords as $keyword) {
            $keyword = trim(strtolower($keyword));
            if (!empty($keyword) && str_contains($searchText, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get match score for a transaction (higher = better match)
     */
    public function getMatchScore($transaction)
    {
        if (empty($this->keywords)) {
            return 0;
        }

        $keywords = $this->getKeywordsArrayAttribute();
        $searchText = strtolower($transaction->partnername . ' ' . $transaction->reference . ' ' . $transaction->partneriban);
        $score = 0;

        foreach ($keywords as $keyword) {
            $keyword = trim(strtolower($keyword));
            if (!empty($keyword)) {
                $count = substr_count($searchText, $keyword);
                $score += $count * strlen($keyword); // Longer keywords get higher weight
            }
        }

        return $score;
    }
}
