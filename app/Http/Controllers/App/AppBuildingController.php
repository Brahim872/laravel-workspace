<?php

namespace App\Http\Controllers\App;

use App\Events\Created;
use App\Http\Controllers\Controller;
use App\Http\Resources\WorkspaceResource;
use App\Models\AppBuilding;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AppBuildingController extends Controller
{




    public function rules()
    {
        return [
            'name' => 'required',
        ];
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,$id)
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            return returnValidatorFails($validator);
        }

        $model = AppBuilding::create([
            'name' => $request->name,
            'workspace_id' => $id,
            'user_id' => returnUserApi()->id,
        ]);

        $workspace = Workspace::find($id);

        $workspace->update(['count_app_building'=>($workspace->count_app_building - 1)]);

         event(new Created($model));
        return returnResponseJson(['app'=>$model], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(AppBuilding $appBuilding)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AppBuilding $appBuilding)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AppBuilding $appBuilding)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AppBuilding $appBuilding)
    {
        //
    }
}
