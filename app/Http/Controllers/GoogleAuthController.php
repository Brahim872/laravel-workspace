<?php

namespace App\Http\Controllers;

use App\Http\Middleware\EnsureNotConnected;
use App\Http\Resources\InvitationResource;
use App\Http\Resources\UserResource;
use App\Models\Invite;
use App\Models\User;
use Google\Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{

    public function redirectToGoogle()
    {

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();
            // Apply the EnsureNotConnected middleware


            $request = (new \Illuminate\Http\Request)->merge(['email' => $user->email]);
            $middleware = app(EnsureNotConnected::class);


            return $middleware->handle($request, function ($request) use ($user) {
                $findUser = User::where('email', '=', $user->email)->first();

                if ($findUser) {
                    $findUser->update([
                        'social_id' => $user->id,
                        'social_type' => "google",
                        'ip_address' => request()->ip(),
                        'device' => request()->header('User-Agent'),
                    ]);

                    auth()->login($findUser);
                    $token = auth()->user()->createToken('auth-token')->plainTextToken;

                    $userResource = new UserResource(auth()->user(), $token);

                    return returnResponseJson(['user' => $userResource], Response::HTTP_OK);
                } else {
                    $user = User::create([
                        'name' => $user->name,
                        'email' => $user->email,
                        'social_id' => $user->id,
                        'social_type' => "google",
                        'password' => Hash::make($user->email),
                    ]);

                    $user->markEmailAsVerified();
                    $token = $user->createToken('auth-token')->plainTextToken;

                    return returnResponseJson([
                        'user' => new UserResource($user, $token),
                    ], Response::HTTP_OK);

                }

            });
        } catch (Exception $e) {
            dd($e->getMessage());
        }


    }
}
