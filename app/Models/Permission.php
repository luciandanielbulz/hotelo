<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['name', 'description', 'category'];

    /**
     * The roles that belong to the permission.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission', 'permission_id', 'role_id');
    }

    /**
     * Get all unique categories
     */
    public static function getCategories()
    {
        return self::whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');
    }
}