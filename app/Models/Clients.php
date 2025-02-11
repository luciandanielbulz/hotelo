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
        'max_upload_size'
    ];
}
