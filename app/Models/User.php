<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'name',
        'lastname',
        'email',
        'role_id',
        'client_id',
        'password',
        'isactive'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Beziehung zur Rolle
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    // Prüfen, ob der Benutzer eine bestimmte Rolle hat
    public function hasRole($role)
    {
        return $this->role && $this->role->name === $role;
    }

    // Prüfen, ob der Benutzer eine Berechtigung hat
    public function hasPermission($permission)
    {
        $roleId = $this->role_id;

        return \DB::table('permissions')
            ->join('role_permission', 'permissions.id', '=', 'role_permission.permission_id')
            ->where('role_permission.role_id', $roleId)
            ->where('permissions.name', $permission)
            ->exists();
    }
}
