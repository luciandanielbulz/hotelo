<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taxrates extends Model
{
    use HasFactory;
    protected $table = 'taxrates';

    public function offers()
    {
        return $this->hasMany(Offers::class, 'tax_id', 'id');
    }
}
