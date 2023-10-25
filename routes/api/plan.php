<?php


use App\Http\Controllers\PlanController;




Route::post('/plans', [PlanController::class, 'index'])
    ->middleware(['auth:sanctum', 'throttle:100,1']);






Route::middleware(['auth:sanctum', 'hasWorkspace'])->prefix('workspace/{id}')->group(function () {





});
