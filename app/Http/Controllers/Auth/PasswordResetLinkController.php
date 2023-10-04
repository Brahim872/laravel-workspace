<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PasswordResetLinkController extends Controller
{


    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
        ];
    }



    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            return returnResponseJson(['errors'=>$validator->messages()], Response::HTTP_BAD_REQUEST);
        }

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );


        if ($status != Password::RESET_LINK_SENT) {
            return returnResponseJson(['message' => __($status)],400);
        }

        return returnResponseJson(['message' => __($status)],200);
    }
}
