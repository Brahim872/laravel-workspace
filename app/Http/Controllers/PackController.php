<?php

namespace App\Http\Controllers;

use App\Models\Pack;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class PackController extends Controller
{
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
    public function store(Request $request, $id)
    {
        try {
            if (!$request->pack){
                return returnResponseJson([
                    'message'=>"Must choose the pack",
                ],Response::HTTP_BAD_REQUEST);
            }

            $workspace = Workspace::find($id);
            $workspace->assignRole($request->pack);

            return returnResponseJson([
                'message'=>"select pack success",
            ],Response::HTTP_OK);

        }catch (\Exception $e){
            return returnResponseJson([
                'message'=>$e->getMessage(),
                'file'=>$e->getFile()." / ".$e->getLine(),
            ],Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pack $pack)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pack $pack)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pack $pack)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pack $pack)
    {
        //
    }
}
