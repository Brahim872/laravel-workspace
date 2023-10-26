<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Resources\Board\BoardListResource;
use App\Http\Resources\Board\BoardResource;
use App\Models\AppBoard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AppBoardController extends Controller
{


    public function rules()
    {
        return [
            'name' => 'required|unique:app_boards,name,NULL,id,user_id,' . returnUserApi()->id,
        ];
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $cacheKey = 'apps_board';
            $apps = Cache::remember('apps_list_' . $cacheKey, 60, function (){

                $boards = returnUserApi()->boards;

                return returnResponseJson(['boards' => new BoardListResource($boards)], Response::HTTP_OK);
            });
            return $apps;
        } catch (\Exception $e) {
            return returnCatchException($e);
        }
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
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), $this->rules(), [
                'name.unique' => 'The name has already been for the current user.',
                'name.required' => 'The name field is required.',
            ]);

            if ($validator->fails()) {
                return returnValidatorFails($validator);
            }

            $model = AppBoard::create([
                'name' => $request->name,
                'user_id' => returnUserApi()->id,
            ]);

            if ($model) {

                return returnResponseJson(['board' => new BoardResource($model)], Response::HTTP_OK);

            };

        } catch (\Exception $e) {
            return returnCatchException($e);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(AppBoard $appBoard)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AppBoard $appBoard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AppBoard $appBoard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AppBoard $appBoard)
    {
        //
    }
}
