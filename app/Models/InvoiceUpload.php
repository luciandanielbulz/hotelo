<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceUpload extends Model
{
    protected $table = 'invoice_uploads';
    protected $fillable = [
        'filepath',
        'invoice_date',
        'description',
        'invoice_number',
        'invoice_vendor',
        'payment_type',
        'type',
        'tax_type',
        'amount',
        'currency_id',
        'net_amount',
        'tax_amount',
        'tax_rate',
        'client_id',
    ];

    protected $dates = ['invoice_date'];

    protected $casts = [
        'amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
    ];

    /**
     * Beziehung zur Währung
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Accessor für payment_type mit Fallback
     */
    public function getPaymentTypeAttribute($value)
    {
        // Falls Spalte nicht existiert oder Wert null ist, gebe Standard zurück
        return $value ?? 'elektronisch';
    }

    /**
     * Prüfe ob payment_type Spalte existiert
     */
    public static function hasPaymentTypeColumn()
    {
        return \Schema::hasColumn('invoice_uploads', 'payment_type');
    }

    /**
     * Berechne Netto- und Steuerbetrag basierend auf Eingabe
     */
    public function calculateAmounts($inputAmount, $taxRate, $isBrutto = true)
    {
        $this->tax_rate = $taxRate;
        
        if ($isBrutto) {
            // Bruttoeingabe - berechne Netto und Steuer
            $this->amount = $inputAmount;
            $this->net_amount = $inputAmount / (1 + ($taxRate / 100));
            $this->tax_amount = $inputAmount - $this->net_amount;
        } else {
            // Nettoeingabe - berechne Brutto und Steuer  
            $this->net_amount = $inputAmount;
            $this->tax_amount = $inputAmount * ($taxRate / 100);
            $this->amount = $inputAmount + $this->tax_amount;
        }
    }

    /**
     * Formatierte Anzeige des Betrags mit Währung
     */
    public function getFormattedAmountAttribute()
    {
        if (!$this->amount || !$this->currency) {
            return null;
        }
        
        return number_format($this->amount, 2, ',', '.') . ' ' . $this->currency->symbol;
    }
}
