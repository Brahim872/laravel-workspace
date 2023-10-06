<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvitationResource;
use App\Http\Resources\UserResource;
use App\Models\Invite;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @param Request $request
     * @return Response
     */

    public function rules(): array
    {
        return [
           // 'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', Rules\Password::defaults()],
        ];
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), $this->rules());


        if ($validator->fails()) {
            return returnValidatorFails($validator);
        }


        $user = User::create([
            'name' => explode('@',$request->email)[0],
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('user');

        if (!$this->validInvitation($request)) {
            event(new Registered($user));

            return returnResponseJson(['message' => 'verification-link-sent'], Response::HTTP_OK);
        } else {

            $user->markEmailAsVerified();
            $token = $user->createToken('auth-token')->plainTextToken;

            return returnResponseJson([
                'user' => new UserResource($user, $token),
                'invitations' => new InvitationResource(Invite::where('email', '=', $user->email)->first()),
            ], Response::HTTP_OK);
        }

    }

    public function validInvitation($invitation)
    {
        $inv = Invite::where('token', '=', $invitation->token)
            ->where('email', '=', $invitation->email)
            ->whereNull(['refused_at', 'accepted_at'])
            ->count();


        return $inv > 0;
    }
}
