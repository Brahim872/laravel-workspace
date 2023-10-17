<?php


use App\Http\Controllers\PlanController;




Route::post('/plans', [PlanController::class, 'index'])
    ->middleware(['auth:sanctum', 'throttle:100,1']);



Route::middleware(['auth:sanctum', 'hasWorkspace'])->prefix('workspace/{id}')->group(function () {



    Route::post('plan-workspace', [PlanController::class, 'store'])
        ->middleware('role:plan_two|free')
        ->name('plan.workspace');


});
