<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;
use App\Http\Resources\App\AppListResource;
use App\Http\Resources\App\AppResource;
use App\Http\Resources\Board\BoardListResource;
use App\Http\Resources\Board\BoardResource;
use App\Models\Apps;
use App\Models\Board;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class BoardController extends Controller
{


    public function rules()
    {
        return [
            'name' => 'required|unique:boards,name,NULL,id,user_id,' . returnUserApi()->id,
        ];
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $cacheKey = 'boards';
            $apps = Cache::remember($cacheKey, 60, function () {

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
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {


        try {
            $app = Apps::find($request->app_id);

            return returnResponseJson([
//                'board' => new BoardResource($model),
                'app' => new AppResource($app),
            ], Response::HTTP_OK);


        } catch (\Exception $e) {
            return returnCatchException($e);
        }
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

            $model = Board::create([
                'name' => $request->name,
                'user_id' => returnUserApi()->id,
                'is_public' => true,
            ]);
            if ($request->app_id) {
                $model->apps()->detach();
                $model->apps()->attach($request->app_id);
            }

            if ($model) {
                return returnResponseJson([
                    'board' => new BoardResource($model),
                    'apps' => new AppListResource(Apps::limit(10)->get()),
                ], Response::HTTP_OK);
            };

        } catch (\Exception $e) {
            return returnCatchException($e);
        }

    }

    /**
     * Add App To Board.
     * @param Request $request
     */
    public function addToBoard(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),
                [
                    'board_id' => 'required',
                    'app_id' => 'required|unique:app_boards,app_id,NULL,id,board_id,' . $request->board_id
                ], [
                    'app_id.unique' => 'this app already saved on this board.',
                ]);
            if ($validator->fails()) {
                return returnValidatorFails($validator);
            }


            $model = Board::find($request->board_id);

//            $model->apps()->detach();
            $model->apps()->attach($request->app_id);

            if ($model) {
                return returnResponseJson([
                    'message' => "The app has been saved successfully",
                ], Response::HTTP_OK);
            };

        } catch (\Exception $e) {
            return returnCatchException($e);
        }
    }


    /**
     * Add App To Board.
     * @param Request $request
     */
    public function removeToBoard(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),
                [
                    'board_id' => 'required',
                    'app_id' => 'required'
                ]);
            if ($validator->fails()) {
                return returnValidatorFails($validator);
            }


            $model = Board::find($request->board_id);

            $model->apps()->detach($request->app_id);
//            $model->apps()->attach($request->app_id);

            if ($model) {
                return returnResponseJson([
                    'message' => "The app has been removed successfully",
                ], Response::HTTP_OK);
            };

        } catch (\Exception $e) {
            return returnCatchException($e);
        }
    }


    /**
     * Add App To Board.
     * @param Request $request
     */
    public function delete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),
                [
                    'board_id' => 'required',
                ]);
            if ($validator->fails()) {
                return returnValidatorFails($validator);
            }


            $model = Board::find($request->board_id);
            $model->apps()->detach();
            $model->delete();

            if ($model) {
                return returnResponseJson([
                    'message' => "The board has been removed successfully",
                ], Response::HTTP_OK);
            };

        } catch (\Exception $e) {
            return returnCatchException($e);
        }
    }
}
