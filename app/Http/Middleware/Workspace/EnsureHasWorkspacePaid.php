<?php

namespace App\Http\Middleware\Workspace;


use Carbon\Carbon;
use Closure;

class EnsureHasWorkspacePaid
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

        $workspaceId = $request->route('id');
        $getCurrentWorkspace = returnUserApi()->getCurrentWorkspace();


        if (returnUserApi() && (!$getCurrentWorkspace || $getCurrentWorkspace->id != $workspaceId)) {
            return returnWarningsResponse([
                'message' => 'you must choose a current workspace [' . $getCurrentWorkspace->id . ']',
            ]);
        }

        if ($getCurrentWorkspace->plan_id != 1) {

            $workspaceOrder = $getCurrentWorkspace->orders->where('status', '=', 'paid')
                ->where('date_end', '>=', Carbon::now())
                ->where('order_type', '=', "subscribe")
                ->first();

            if (is_null($workspaceOrder)) {
                return returnWarningsResponse(['message' => 'This account is unpaid']);
            }

        }


        return $next($request);
    }

}
