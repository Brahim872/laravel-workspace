<?php

namespace App\Http\Middleware;

//use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Illuminate\Http\Request;

class Authenticate
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (auth()->guard($guard)->guest()) {
            return returnResponseJson(['message'=>'Unauthorized.'], 401);
        }

        return $next($request);
    }
}
