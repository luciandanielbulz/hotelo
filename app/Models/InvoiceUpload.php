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
        'client_id',
    ];

    protected $dates = ['invoice_date'];

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
}
