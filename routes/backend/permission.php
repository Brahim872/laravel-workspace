<?php

/*
  |--------------------------------------------------------------------------
  | Backend Permission Routes
  |--------------------------------------------------------------------------
  |
  | This file is where you may define all of the routes that are handled
  | by your backendlication. Just tell Laravel the URIs it should respond
  | to using a Closure or controller method. Build something great!
  |
 */

use App\Http\Controllers\Acl\PermissionController;

Route::group(['prefix' => 'permissions', 'as' => 'backend.permission.'], function () {

    Route::post('/', [PermissionController::class, 'index'])
//        ->middleware(['permission:backend.permission.read'])
        ->name('index');

    Route::post('/update', [PermissionController::class, 'postIndex'])
//        ->middleware(['permission:backend.permission.update'])
        ->name('update');
});
