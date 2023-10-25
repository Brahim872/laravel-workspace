<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Resources\App\AppListResource;
use App\Models\Apps;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AppsController extends Controller
{

    public function index(Request $request)
    {
        try {

//            $apps = Cache::remember('apps', 60, function () use ($request) {
                // This closure will be executed if the 'apps' key is not found in the cache
                $_apps = Apps::select('*')->where('name','=',$request->s)->get();
                return returnResponseJson(['apps' => new AppListResource($_apps)], Response::HTTP_OK);
//            });

//            return $apps;
        } catch (\Exception $e) {
            return returnCatchException($e);
        }
    }

}
