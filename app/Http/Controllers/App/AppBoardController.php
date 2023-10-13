<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\AppBoard;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppBoardController extends Controller
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
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), $this->rules());

            if ($validator->fails()) {
                return returnValidatorFails($validator);
            }

            $model = AppBoard::create([
                'name' => $request->name,
                'user_id' => returnUserApi()->id,
                ]);




        }catch (\Exception $e){
            return returnCatchException($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(AppBoard $appBoard)
    {
        //
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
