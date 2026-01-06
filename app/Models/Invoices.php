<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Invoicepositions;

class Invoices extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'client_version_id',
        'number',
        'tax_id',
        'condition_id',
        'number',
        'comment',
        'description',
        'document_footer',
        'depositamount',
        'status',
        'created_by',
        'client_version_id',
        'dunning_stage',
        'due_date',
        'dunning_stage_date',
    ];

    public function invoicePositions()
{
    return $this->hasMany(Invoicepositions::class, 'invoice_id');
}

    /**
     * Beziehung zum Kunden
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Beziehung zur Client-Version
     */
    public function clientVersion()
    {
        return $this->belongsTo(Clients::class, 'client_version_id');
    }

    /**
     * Beziehung zur Condition
     */
    public function condition()
    {
        return $this->belongsTo(Condition::class, 'condition_id');
    }
}
