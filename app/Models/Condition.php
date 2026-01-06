<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Condition extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'conditionname', 
        'client_id',
        'dunning_reminder_days',
        'dunning_first_stage_days',
        'dunning_second_stage_days',
        'dunning_third_stage_days'
    ];

    public $timestamps = true;

    public function client()
    {
        return $this->belongsTo(Clients::class, 'client_id', 'id');
    }

    public function offers()
    {
        return $this->hasMany(Offers::class, 'condition_id', 'id');
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'condition_id', 'id');
    }
}
