<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProfileResource;

class UserController extends Controller
{

    public function index(){
        return new ProfileResource(auth('sanctum')->user());
    }

}
