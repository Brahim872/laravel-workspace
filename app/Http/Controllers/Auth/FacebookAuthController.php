<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Middleware\EnsureNotConnected;
use App\Http\Resources\UserResource;
use App\Models\User;
use Google\Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class FacebookAuthController extends Controller
{

    public function redirectToFacebook()
    {

        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->stateless()->user();
            // Apply the EnsureNotConnected middleware

            $findUser = User::where('email', '=', $user->email)->first();
            if ($findUser && !$findUser->tokens->isEmpty()) {
                return redirect(env('FRONTEND_URL') . '/auth/login?error=This email already connected');

            }

            if ($findUser) {
                $findUser->update([
                    'social_id' => $user->id,
                    'social_type' => "facebook",
                    'ip_address' => request()->ip(),
                    'device' => request()->header('User-Agent'),
                ]);

                auth()->login($findUser);
                $token = auth()->user()->createToken('auth-token')->plainTextToken;

//                    $userResource = new UserResource(auth()->user(), $token);

                return redirect(env('FRONTEND_URL') . '/auth/login?token=' . $token);
//                    return returnResponseJson(['user' => $userResource], Response::HTTP_OK);
            } else {
                $user = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'social_id' => $user->id,
                    'social_type' => "facebook",
                    'password' => Hash::make($user->email),
                ]);

                $user->markEmailAsVerified();
                $token = $user->createToken('auth-token')->plainTextToken;

                return redirect(env('FRONTEND_URL') . '/auth/login?token=' . $token);
            }

        } catch (\Exception $e) {
            return redirect(env('FRONTEND_URL') . '/auth/login?error=' . $e->getMessage());
        }

    }
}
