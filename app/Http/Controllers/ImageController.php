<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{


    public function changeAvatar(Request $request){
        try {
            $user = returnUserApi();

            $image = new Image;

            $image['url'] = $request->file('avatar')->store('public/avatar');


            $user->images()->save($image);

            return response($user, Response::HTTP_CREATED);



        }catch (\Exception $e){
            return returnCatchException($e);
        }
    }}
