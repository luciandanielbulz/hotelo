<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    use HasFactory;

    protected $fillable = [
        'clientname',
        'companyname',
        'business',
        'address',
        'postalcode',
        'location',
        'email',
        'phone',
        'tax_id',
        'webpage',
        'bank',
        'accountnumber',
        'vat_number',
        'bic',
        'smallbusiness',
        'logo',
        'logoheight',
        'logowidth',
        'signature',
        'style',
        'lastoffer',
        'lastinvoice',
        'offermultiplikator',
        'invoicemultiplikator',
        'max_upload_size',
        'company_registration_number',
        'tax_number',
        'management',
        'regional_court',
        'color',
        'invoice_number_format',
        'invoice_prefix',
        'offer_prefix'
    ];

    /**
     * Generiert die nächste Rechnungsnummer basierend auf dem gewählten Format
     */
    public function generateInvoiceNumber()
    {
        $format = $this->invoice_number_format ?? 'YYYY*1000+N';
        $rawNumber = $this->lastinvoice ?? 0;
        $multiplier = $this->invoicemultiplikator ?? 1000;
        $year = now()->year;
        $shortYear = substr($year, -2);

        switch ($format) {
            case 'YYYYNN':
                return $year . str_pad($rawNumber + 1, 4, '0', STR_PAD_LEFT);
            
            case 'YY*1000+N':
                return $shortYear * $multiplier + 1000 + $rawNumber;
            
            case 'YYYY_MM+N':
                $month = str_pad(now()->month, 2, '0', STR_PAD_LEFT);
                return $year . '_' . $month . str_pad($rawNumber + 1, 3, '0', STR_PAD_LEFT);
            
            case 'N':
                return $rawNumber + 1;
            
            case 'custom':
                // Hier kann später eine benutzerdefinierte Logik implementiert werden
                return $year * $multiplier + 1000 + $rawNumber;
            
            default: // 'YYYY*1000+N'
                return $year * $multiplier + 1000 + $rawNumber;
        }
    }

    /**
     * Generiert die nächste Angebotsnummer basierend auf dem gewählten Format
     * Verwendet das gleiche Format wie Rechnungen, aber mit Angebots-spezifischen Werten
     */
    public function generateOfferNumber()
    {
        $format = $this->invoice_number_format ?? 'YYYY*1000+N'; // Verwendet das gleiche Format wie Rechnungen
        $rawNumber = $this->lastoffer ?? 0;
        $multiplier = $this->offermultiplikator ?? 1000;
        $year = now()->year;
        $shortYear = substr($year, -2);

        switch ($format) {
            case 'YYYYNN':
                return $year . str_pad($rawNumber + 1, 4, '0', STR_PAD_LEFT);
            
            case 'YY*1000+N':
                return $shortYear * $multiplier + 6000 + $rawNumber; // +6000 für Angebote (wie im ursprünglichen Code)
            
            case 'YYYY_MM+N':
                $month = str_pad(now()->month, 2, '0', STR_PAD_LEFT);
                return $year . '_' . $month . str_pad($rawNumber + 1, 3, '0', STR_PAD_LEFT);
            
            case 'N':
                return $rawNumber + 1;
            
            case 'custom':
                // Hier kann später eine benutzerdefinierte Logik implementiert werden
                return $year * $multiplier + 6000 + $rawNumber;
            
            default: // 'YYYY*1000+N'
                return $year * $multiplier + 6000 + $rawNumber; // +6000 für Angebote
        }
    }
}
