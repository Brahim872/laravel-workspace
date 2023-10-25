<?php

use App\Http\Controllers\PlanController;




Route::post('plan-create', [PlanController::class, 'store'])
    ->name('plan.workspace');

Route::post('price-update/{plan}', [PlanController::class, 'priceUpdate'])
    ->name('price.update');


Route::post('plan-update/{plan}', [PlanController::class, 'update'])
    ->name('plan.update');
