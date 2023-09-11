<?php


namespace App\Http\Middleware;


use App\Models\User;
use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class hasWorkspace
{

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (returnUserApi() && returnUserApi()->hasWorkspaces()->count() <= 0) {
            return  returnResponseJson(['message' => 'you must have be a workspace'],409);
        }

        return $next($request);
    }
}
