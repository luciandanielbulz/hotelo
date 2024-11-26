<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle($request, Closure $next, $permission)
    {
        $user = Auth::user();

        // Prüfe, ob der Benutzer die Berechtigung hat
        if (!$user->hasPermission($permission)) {
            abort(403, 'Access Denied');
        }

        return $next($request);
    }
}
