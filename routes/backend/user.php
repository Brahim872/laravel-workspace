<?php

/*
  |--------------------------------------------------------------------------
  | Backend User Routes
  |--------------------------------------------------------------------------
  |
  | This file is where you may define all of the routes that are handled
  | by your backendlication. Just tell Laravel the URIs it should respond
  | to using a Closure or controller method. Build something great!
  |
 */

use App\Http\Controllers\Acl\UserController;

Route::group(['prefix' => 'user', 'as' => 'backend.user.'], function () {
    /*
     * List of all Users
     */
    Route::post('/', [UserController::class, 'index'])
//        ->middleware(['permission:backend.user.read'])
        ->name('index');


    /*
     * Ajax deleteion of one User
     */
    Route::delete('delete/{id}', [UserController::class, 'delete'])
//        ->middleware(['permission:backend.user.delete'])
        ->where('id', '[0-9]+')
        ->name('delete');

    /*
     * Creation of one User
     */
    Route::get('/create', [UserController::class, 'create'])
//        ->middleware(['permission:backend.user.create'])
        ->name('create');

    /*
     * Submited form for creation of one User
     */
    Route::post('/create', [UserController::class, 'store'])
//        ->middleware(['permission:backend.user.create'])
        ->name('store');

    /*
     * Edition of one User
     */
    Route::get('/edit/{id}', [UserController::class, 'edit'])
//        ->middleware(['permission:backend.user.update'])
        ->where('id', '[0-9]+')
        ->name('edit');


    /*
     * Submited form for edition of one User
     */
    Route::post('/edit/{id}', [UserController::class, 'update'])
//        ->middleware(['permission:backend.user.update'])
        ->where('id', '[0-9]+')
        ->name('update');

    /*
     * AJax activation or unactivation of one User
     */
    Route::put('/switchactive/{id}', [UserController::class, 'executeSwitch'])
        ->where('id', '[0-9]+')
//        ->middleware(['ajax','permission:backend.user.update'])
        ->name('switchActive');

    /*
     * AJax activation or unactivation of one User
     */
    Route::put('/switchverify/{id}', [UserController::class, 'executeVerify'])
        ->where('id', '[0-9]+')
//        ->middleware(['ajax','permission:backend.user.update'])
        ->name('switchVerify');

});


