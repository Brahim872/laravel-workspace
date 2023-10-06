<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Tools;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\Sanctum;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     * @param Request $request
     * @return Response
     */

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function store(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), $this->rules());

            if ($validator->fails()) {
                return returnResponseJson(['errors'=>$validator->messages()], Response::HTTP_BAD_REQUEST);
            }

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

                auth()->user()->update([
                    'ip_address'=>request()->ip(),
                    'device'=> request()->header('User-Agent'),
                ]);

                $token = auth()->user()->createToken('auth-token')->plainTextToken;

                $userResource = new UserResource(auth()->user(),$token);

                return returnResponseJson(['user'=>$userResource,'token'=>$token],Response::HTTP_OK,'user' );
            }

            return returnResponseJson(["error" => "That credentials not compatible with data."], Response::HTTP_BAD_REQUEST);

        } catch (\Exception $e) {
            return returnResponseJson(["message" => $e->getMessage(),"file" => $e->getFile(),"line" => $e->getLine()], Response::HTTP_BAD_REQUEST);


        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
//        auth('sanctum')->user()->update([
//           'ip_address'=>null,
//           'device'=>null,
//        ]);
        $log = auth('sanctum')->user()->tokens()->delete();
        return returnResponseJson(['message'=>'logout success'],200);
    }
}
