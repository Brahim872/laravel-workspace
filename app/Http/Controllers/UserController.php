<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProfileResource;
use App\Http\Resources\UserResource;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Image as InterventionImage;
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
            return returnResponseJson([
                'message' => $e->getMessage(),
                'getCode' => $e->getCode(),
            ], 500);
        }

    }


    public function update(Request $request)
    {


        try {
            $validator = Validator::make($request->all(), $this->rulesUpdate());

            if ($validator->fails()) {
                return returnResponseJson($validator->messages(), Response::HTTP_BAD_REQUEST);
            }

            $user = returnUserApi()->update($request->all());


            if ($user) {
                return returnResponseJson([
                    'message' => "update success",
                    'user' => new UserResource( returnUserApi()),
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return returnResponseJson([
                'message' => $e->getMessage(),
                'getCode' => $e->getCode(),
            ], 500);
        }


    }




    public function changeAvatar(Request $request)
    {

        $validator = Validator::make($request->all(), $this->rulesAvatar());

        if ($validator->fails()) {
            return returnResponseJson($validator->messages(), Response::HTTP_BAD_REQUEST);
        }

        return returnUserApi()->changeAvatar($request->file('avatar'),'images/users/avatars');
    }
}
