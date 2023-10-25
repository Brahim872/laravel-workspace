<?php

use App\Http\Controllers\App\AppsController;
use App\Http\Controllers\PlanController;


Route::get('getget',[AppsController::class, 'index'])
    ->name('plan.workspace');




require __DIR__.'/backend/plan.php';
require __DIR__.'/backend/role.php';
