<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Resources\App\AppListResource;
use App\Models\Apps;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AppsController extends Controller
{

    public function index(Request $request)
    {
        try {
            $cacheKey = 'apps_list_' . $request->s;
            DB::enableQueryLog();
            $apps = Cache::remember('apps_list_' . $cacheKey, 60, function () use ($request) {
                // This closure will be executed if the 'apps' key is not found in the cache
                $_apps = Apps::where('type', 'like', $request->s . '%')->get();

                return returnResponseJson([
                    'queries' => DB::getQueryLog(),
                    'countAll' => Apps::count(),
                    'count' => $_apps->count(),
                    'apps' => new AppListResource($_apps)], Response::HTTP_OK);
            });

            return $apps;
        } catch (\Exception $e) {
            return returnCatchException($e);
        }
    }

}
