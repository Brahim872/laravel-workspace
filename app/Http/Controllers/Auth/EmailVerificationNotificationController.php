<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {


        $user = User::where('email','=',$request->email)->first();

        if(!$user){
            return returnValidatorFails(['email' => 'this email not match']);
        }

        if ($user->hasVerifiedEmail()) {
            return returnValidatorFails(['email' => 'your email already verified']);
        }

        $user->sendEmailVerificationNotification();
        return returnResponseJson(['message' => 'verification-link-sent'], Response::HTTP_OK);

    }
}
