<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'lastinvoice',
        'lastoffer',
        'invoicemultiplikator',
        'offermultiplikator',
        'invoice_number_format',
        'offer_number_format',
        'invoice_prefix',
        'offer_prefix',
        'max_upload_size',
    ];

    protected $casts = [
        'lastinvoice' => 'integer',
        'lastoffer' => 'integer',
        'invoicemultiplikator' => 'integer',
        'offermultiplikator' => 'integer',
    ];

    /**
     * Beziehung zum ursprünglichen Client
     */
    public function client()
    {
        return $this->belongsTo(Clients::class, 'client_id');
    }

    /**
     * Generiere die nächste Rechnungsnummer basierend auf dem Format
     */
    public function generateInvoiceNumber()
    {
        $format = $this->invoice_number_format ?? 'YYYYNN';
        $number = $this->lastinvoice + 1;
        
        return $this->generateNumber($format, $number, $this->invoicemultiplikator);
    }

    /**
     * Generiere die nächste Angebotsnummer basierend auf dem Format
     */
    public function generateOfferNumber()
    {
        $format = $this->offer_number_format ?? $this->invoice_number_format ?? 'YYYYNN';
        $number = $this->lastoffer + 1;
        
        return $this->generateNumber($format, $number, $this->offermultiplikator);
    }

    /**
     * Gemeinsame Logik für die Nummerngeneration
     */
    private function generateNumber($format, $number, $multiplikator)
    {
        $year = date('Y');
        $yearShort = date('y');
        $month = date('m');
        
        switch ($format) {
            case 'YYYY*1000+N':
                return ($year * 1000) + $number;
            case 'YYYYNN':
                return $year . str_pad($number, 4, '0', STR_PAD_LEFT);
            case 'YY*1000+N':
                return ($yearShort * 1000) + $number;
            case 'YYYY_MM+N':
                return $year . '_' . $month . str_pad($number, 3, '0', STR_PAD_LEFT);
            case 'YYYY*10000+N+1000':
                return ($year * 10000) + ($number + 1000);
            case 'YYYY*10000+N+6000':
                return ($year * 10000) + ($number + 6000);
            case 'N':
                return $number;
            default:
                // Fallback für unbekannte Formate
                return $year . str_pad($number, 4, '0', STR_PAD_LEFT);
        }
    }
}
