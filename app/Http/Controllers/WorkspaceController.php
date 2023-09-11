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

class WorkspaceController extends Controller
{
    use CrudTrait;

    protected $prefixName = "workspace";
    protected $model = Workspace::class;


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $workspace = returnUserApi()->workspaces()->get();

        return returnResponseJson(['workspaces'=> new WorkspaceResource($workspace,true)],200);
    }

    public function responseStore($saveModel)
    {
      return  returnResponseJson([
            'workspace'=> new WorkspaceResource($saveModel)
        ],200);
    }

    protected function afterSave(array $attributes, $model)
    {
        $model->users()->detach();

        $model->users()->attach(returnUserApi()->id,[
            'type_user' => 0 // = admin
        ]);

        auth('sanctum')->user()->update(['current_workspace'=>$model->id]);

    }
}
