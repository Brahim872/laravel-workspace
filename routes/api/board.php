<?php

use App\Http\Controllers\Board\BoardController;

Route::prefix('/board')->group(function () {


    Route::post('/', [BoardController::class, 'index'])
        ->name('index.board');


    Route::get('create', [BoardController::class, 'create'])
        ->name('create.board');


    Route::post('create', [BoardController::class, 'store'])
        ->name('store.board');


    Route::post('add-app', [BoardController::class, 'addToBoard'])
        ->name('add-app.board');


});
