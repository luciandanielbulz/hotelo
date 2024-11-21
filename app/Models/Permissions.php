<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    protected $table = 'permissions'; // Sicherstellen, dass die Tabelle korrekt zugeordnet ist

    protected $fillable = [
        'name',
        'description'
    ];//

    public function roles()
{
    return $this->belongsToMany(Permissions::class, 'role_permissions', 'permission_id', 'role_id');
}
}

