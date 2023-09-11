<?php


namespace App\Http\Middleware;


use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureNotConnected
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */


    public function handle($request, Closure $next)
    {
        $user = User::where('email','=',$request->email)->first();

        if (!$user->tokens->isEmpty()) {
            // User does not have a Sanctum token
            return returnResponseJson([
                'message'=>'This email already connected',
                'device'=>$user->device,
            ],401);
        }

        return $next($request);
    }
}
