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
        'address',
        'postalcode',
        'location',
        'country',
        'phone',
        'fax',
        'email',
        'tax_id',
        'condition_id',
        'salutation_id',
        'title',
        'active',
        'emailsubject',
        'emailbody',
        'issoftdeleted',
        'client_id'
    ];

}
