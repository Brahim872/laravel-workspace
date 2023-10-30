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
use App\Http\Controllers\Plan\PlanFeaturesController;

Route::group(['prefix' => 'permissions-plans', 'as' => 'permission.plans.'], function () {

    Route::post('/', [\App\Http\Controllers\Plan\PlanFeaturesController::class, 'index'])
//        ->middleware(['permission:backend.permission.read'])
        ->name('index');

    Route::post('/create', [\App\Http\Controllers\Plan\PlanFeaturesController::class, 'store'])
//        ->middleware(['permission:backend.permission.read'])
        ->name('create');

    Route::post('/update', [PlanFeaturesController::class, 'postIndex'])
//        ->middleware(['permission:backend.permission.update'])
        ->name('update');
});
