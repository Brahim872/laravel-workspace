<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProfileResource;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

class UserController extends Controller
{

    public function index(){
        try {

            return new ProfileResource(auth('sanctum')->user());

        } catch (\Exception $e) {
            return returnResponseJson([
                'message'=>$e->getMessage(),
                'getCode'=>$e->getCode(),
            ],500);
        }

    }

}
