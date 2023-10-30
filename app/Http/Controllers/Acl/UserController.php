<?php

namespace App\Http\Controllers\Acl;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;



class UserController extends Controller
{


    public function rulesUpdate(): array
    {
        return [
            'name' => ['string', 'max:255'],
            //  'email' => ['string', 'email', 'max:255', 'unique:' . User::class],
        ];
    }


    public function rulesAvatar(): array
    {
        return [
            'avatar' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ];

    }

    public function index()
    {
        try {

            return returnResponseJson(['user'=>new ProfileResource(auth('sanctum')->user())], Response::HTTP_OK);;

        } catch (\Exception $e) {
            return returnCatchException($e);
        }

    }


    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), $this->rulesUpdate());

            if ($validator->fails()) {
                return returnValidatorFails($validator);
            }

            $user = returnUserApi()->update($request->all());


            if ($user) {
                return returnResponseJson([
                    'message' => "update success",
                    'user' => new ProfileResource( returnUserApi()),
                ], Response::HTTP_OK);
            }

        } catch (\Exception $e) {
            return returnCatchException($e);
        }
    }




    public function changeAvatar(Request $request)
    {

        $validator = Validator::make($request->all(), $this->rulesAvatar());

        if ($validator->fails()) {
            return returnValidatorFails($validator);
        }

        return returnUserApi()->changeAvatar($request->file('avatar'),'images/users/avatars');
    }
}
