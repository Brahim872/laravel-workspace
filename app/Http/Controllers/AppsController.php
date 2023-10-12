<?php

namespace App\Http\Controllers;

use App\Http\Resources\AppResource;
use App\Models\Apps;
use Symfony\Component\HttpFoundation\Response;

class AppsController extends Controller
{

    public function index(){
        try {
            $apps = Apps::get();
            return returnResponseJson(['apps'=>$apps],Response::HTTP_OK);
        }catch (\Exception $e){
            return returnCatchException($e);
        }
    }

}
