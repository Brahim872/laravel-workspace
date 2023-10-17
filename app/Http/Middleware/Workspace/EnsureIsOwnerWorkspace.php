<?php

namespace App\Http\Middleware\Workspace;


use App\Models\Workspace;
use Carbon\Carbon;
use Closure;

class EnsureIsOwnerWorkspace
{

    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @param $functions
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle($request, Closure $next)
    {
        if(is_null(returnUserApi()->workspaces('owner')->first())){
            return returnWarningsResponse(['message'=>'you don\'t have access to this function']);
        };

        return $next($request);
    }

}
