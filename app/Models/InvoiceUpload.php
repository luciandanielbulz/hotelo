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
}
