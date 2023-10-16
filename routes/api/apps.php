<?php


use App\Http\Controllers\App\AppBoardController;
use App\Http\Controllers\App\AppBuildingController;
use App\Http\Controllers\App\AppsController;

Route::middleware(['workspace.paid','checkPlan:plan_one'])->prefix('/workspace/{id}')->group(function () {


    Route::post('charts-apps', [AppsController::class, 'index'])
        ->name('charts.apps');




    Route::post('/create-app', [AppBuildingController::class, 'store'])
        ->middleware(['EnsureHaveAppsToBuilding'])
        ->name('create.app.store');

});
