<?php


namespace App\Http\Middleware;


use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
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
        $user = User::where('email', '=', $request->email)->first();


        if ($user && !$user->tokens->isEmpty()) {
            $user->tokens->each(function (PersonalAccessToken $token) {
                $token->delete();
            });

            // Create a new token for the user
            $token = $user->createToken('auth-token')->plainTextToken;


            // User does not have a Sanctum token
            return returnResponseJson([
                'user' => [
                    'message' => 'This email already connected',
                    'device' => $user->device,
                    'token' => $token,
                ]
            ], Response::HTTP_CONFLICT);
        }

        return $next($request);
    }
}
