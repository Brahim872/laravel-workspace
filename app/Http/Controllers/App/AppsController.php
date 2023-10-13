<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
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
