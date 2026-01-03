<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'companyname',
        'customername',
        'customer_number',
        'address',
        'postalcode',
        'location',
        'country',
        'country_id',
        'phone',
        'fax',
        'email',
        'tax_id',
        'vat_number',
        'condition_id',
        'salutation_id',
        'title',
        'active',
        'emailsubject',
        'emailbody',
        'issoftdeleted',
        'client_id'
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Beziehung zum Client (falls vorhanden).
     */
    public function client()
    {
        return $this->belongsTo(Clients::class);
    }

    /**
     * Beziehung zum Land
     */
    public function countryRelation()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
