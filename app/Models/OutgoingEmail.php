<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutgoingEmail extends Model
{
    use HasFactory;

    // Erlaubte Massenfelder
    protected $table = 'outgoingemails';
    protected $fillable = [
        'type',
        'customer_id',
        'objectnumber',
        'sentdate',
        'getteremail',
        'filename',
        'withattachment',
        'status',
        'client_id',
    ];
}
