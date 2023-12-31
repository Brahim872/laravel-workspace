<?php


namespace App\Http\Middleware\Workspace;


use App\Models\Subscription;
use App\Models\User;
use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureWorkspaceHasRightPlan
{


    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @param $plan
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle($request, Closure $next, $plan)
    {
        $plans = explode('|', $plan);


        $workspaceCurrent = returnUserApi()->getCurrentWorkspace();

        if (is_null($workspaceCurrent->plans()->whereIn('name', $plans)->first())) {
            return returnWarningsResponse([
                'message' => 'You Don\'t have access to this route',
                'notice' => 'this route just for plan(s) [ '. implode(',',$plans).' ]'
            ]);
        }

        return $next($request);
    }

}
