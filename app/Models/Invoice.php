<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    // Definiere die Beziehung zu 'Customer'
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id'); // Angenommen, der Fremdschlüssel ist 'customer_id'
    }
}
