<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
//        $authGuard = Auth::guard('sanctum');
//
//        if ($authGuard->guest()) {
//            throw UnauthorizedException::notLoggedIn();
//        }

        $roles = is_array($role)
            ? $role
            : explode('|', $role);

        if (!returnUserApi()->getCurrentWorkspace()->hasAnyRole($roles)) {
            throw UnauthorizedException::forRoles($roles);
        }


        return $next($request);
    }
}
