<?php


namespace App\Http\Middleware\Workspace;


use App\Models\Order;
use Carbon\Carbon;
use Closure;

class hasWorkspace
{

    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @param $functions
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle($request, Closure $next, $functions = null)
    {

        $_functions = $functions ? explode('|', $functions) : [];

        $workspaceId = $request->route('id');
        $getCurrentWorkspace = returnUserApi()->getCurrentWorkspace();


        if (in_array("current", $_functions) == true || is_null($functions)) {
            if (returnUserApi() && !$getCurrentWorkspace ) {
                return returnWarningsResponse(['message' => 'you must have a workspace']);
            }
        }


        if (in_array("id", $_functions) == true || is_null($functions)) {
            if ($getCurrentWorkspace->id != $workspaceId) {
                return returnWarningsResponse(['message' => 'you must choose a current workspace [' . $getCurrentWorkspace->id . ']']);
            }
        }


        if (in_array("paid", $_functions) == true || is_null($functions)) {
            $checkOrderId = true;

            if ($getCurrentWorkspace->plan_id != 1) {
                $checkOrderId = Order::where('workspace_id', '=', $workspaceId)
                    ->where('status', '=', 'paid')
                    ->where('date_end', '>', Carbon::now())
                    ->whereNotNull('payment_id')
                    ->first();
            }

            if (is_null($checkOrderId) || is_null($getCurrentWorkspace->plan_id)) {
                return returnWarningsResponse(['message' => 'Your account is unpaid']);
            }
        }

        if (!is_null($getCurrentWorkspace->deactivated_at)){
            return returnWarningsResponse(['message' => 'Your account is deactivated']);
        }


        return $next($request);
    }

}
