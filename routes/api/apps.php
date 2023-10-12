<?php


use App\Http\Controllers\App\AppBuildingController;
use App\Http\Controllers\AppsController;

Route::middleware(['workspace.paid'])->prefix('/workspace/{id}')->group(function () {


    Route::post('charts-apps', [AppsController::class, 'index'])
        ->name('charts.apps');


    Route::post('/create-app', [AppBuildingController::class, 'store'])
        ->middleware(['checkPlan:plan_one','EnsureHaveAppsToBuilding'])
        ->name('create.app.store');

});
