<?php


namespace App\Http\Middleware;


use App\Models\Order;
use App\Models\User;
use App\Models\Workspace;
use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHaveAppsToBuilding
{

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $workspaceId = $request->route('id');
        $workspaceCurrent = Workspace::find($workspaceId);
        $countAppBuilding = $workspaceCurrent->count_app_building;

        if ($countAppBuilding <= 0) {
            return returnValidatorFails(['message' => 'The number of applications you can build is '. $countAppBuilding]);
        }

        return $next($request);
    }

}
