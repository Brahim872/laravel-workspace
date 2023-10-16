<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Resources\App\AppListResource;
use App\Models\Apps;
use Symfony\Component\HttpFoundation\Response;

class AppsController extends Controller
{

    public function index(){
        try {
            $apps = Apps::get();
            return returnResponseJson(['apps'=>new AppListResource($apps)],Response::HTTP_OK);
        }catch (\Exception $e){
            return returnCatchException($e);
        }
    }

}
