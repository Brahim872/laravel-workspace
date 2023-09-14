<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::where('email', '=', $request->email)->first();

        $passwordToCheck = $request->password; // Replace with the password you want to check

        if (!$user) {

            return returnResponseJson(['message' => 'Your credentials are not compatible.'], Response::HTTP_FORBIDDEN);

        } else {
            $hashedPasswordFromDatabase = $user->password; // Replace with the hashed password from your database
            if (!Hash::check($passwordToCheck, $hashedPasswordFromDatabase)) {
                return returnResponseJson(['message' => 'Your credentials are not compatible.'], Response::HTTP_FORBIDDEN);
            }
        }

        if (!$user ||
            ($user instanceof MustVerifyEmail &&
                !$user->hasVerifiedEmail())) {
            return returnResponseJson(['message' => 'Your email address is not verified.'], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
