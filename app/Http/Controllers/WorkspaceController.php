<?php

namespace App\Http\Controllers;

use App\Http\Resources\WorkspaceResource;
use App\Models\User;
use App\Models\Workspace;
use App\Traits\CrudTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WorkspaceController extends Controller
{
//    use CrudTrait;

    protected $model = Workspace::class;


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $workspace = returnUserApi()->workspaces()->get();

        return returnResponseJson(['workspaces' => new WorkspaceResource($workspace, true)], 200);
    }


    public function store(Request $request)
    {
        $model = (new $this->model)->create($request->all());

        $model->users()->detach();

        $model->users()->attach(returnUserApi()->id, [
            'type_user' => 0 // = admin
        ]);


        auth('sanctum')->user()->update(['current_workspace' => $model->id]);

        return returnResponseJson([
            'workspace' => new WorkspaceResource($model)
        ], 200);
    }



    protected function afterSave(array $attributes, $model)
    {
        $model->users()->detach();

        $model->users()->attach(returnUserApi()->id, [
            'type_user' => 0 // = admin
        ]);

        auth('sanctum')->user()->update(['current_workspace' => $model->id]);
    }


    public function change(Request $request)
    {
        try {

            if (!returnUserApi()->workspaces()->where('workspace_user.workspace_id', '=', $request->workspace_id)->first()) {
                return returnResponseJson([
                    'message' => 'you dont have access to this workspace ',
                ], Response::HTTP_FORBIDDEN);
            }

            returnUserApi()->update(['current_workspace' => $request->workspace_id]);
            $workspace = Workspace::find($request->workspace_id);

            if (!$workspace) {
                return returnResponseJson([
                    'message' => 'workspace doesnt exist !',
                ], Response::HTTP_FORBIDDEN);
            }

            return returnResponseJson([
                'message' => 'you are switch to workspace: ' . $workspace->name,
                'user' => [
                    'workspace' => new WorkspaceResource($workspace)
                ]
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return returnResponseJson([
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }


    public function update(Request $request, $workspace)
    {
        try {
            $_workspace = Workspace::find($workspace)->update(['name' => $request->name]);
            if ($_workspace) {

                return returnResponseJson([
                    'message' => 'update workspace has been successful',
                    'user' => [
                        'workspace' => new WorkspaceResource(Workspace::find($workspace))
                    ]
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return returnResponseJson([
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
