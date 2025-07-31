<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankData extends Model
{
    use HasFactory;

    protected $table = 'bankdata';

    protected $fillable = [
        'date',
        'partnername',
        'partneriban',
        'partnerbic',
        'partneraccount',
        'partnerroutingnumber',
        'amount',
        'currency',
        'reference',
        'referencenumber',
        'client_id',
        'category_id',
        'type',
        'transaction_id',
        'contained_transaction_id',
    ];

    /**
     * Beziehung zur Kategorie
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Beziehung zum Client
     */
    public function client()
    {
        return $this->belongsTo(Clients::class);
    }
}
