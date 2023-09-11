<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request)
    {


        $user = User::where('email','=',$request->email)->first();

        if(!$user){
            return returnResponseJson(['message' => 'this email not match'], 400);
        }

        if ($user->hasVerifiedEmail()) {
            return returnResponseJson(['message' => 'your email already verified'], 400);
        }

        $user->sendEmailVerificationNotification();
        return returnResponseJson(['message' => 'verification-link-sent'], 400);

//        return response()->json(['status' => 'verification-link-sent']);
    }
}
