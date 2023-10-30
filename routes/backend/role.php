<?php

/*
  |--------------------------------------------------------------------------
  | Backend Role Routes
  |--------------------------------------------------------------------------
  |
  | This file is where you may define all of the routes that are handled
  | by your backendlication. Just tell Laravel the URIs it should respond
  | to using a Closure or controller method. Build something great!
  |
 */

Route::group(['prefix' => 'role', 'as' => 'backOffice.role.'], function () {
    /*
     * List of all Roles
     */
    Route::post('/', [\App\Http\Controllers\Acl\RoleController::class, 'index'])
//        ->middleware(['permission:backend.role.read'])
        ->name('index');

    /*
     * Ajax deleteion of one Role
     */
    Route::get('delete/{id}', [\App\Http\Controllers\Acl\RoleController::class, 'delete'])
//        ->middleware(['permission:backend.role.delete'])
        ->where('id', '[0-9]+')
        ->name('delete');

    /*
     * Creation of one Role
     */

    Route::get('/create', [\App\Http\Controllers\Acl\RoleController::class, 'create'])
//        ->middleware(['permission:backend.role.create'])
        ->name('create');

    /*
     * Submited form for creation of one Role
     */
    Route::post('/create', [\App\Http\Controllers\Acl\RoleController::class, 'store'])
//        ->middleware(['permission:backend.role.create'])
        ->name('store');

    /*
     * Edition of one Role
     */
    Route::get('/edit/{id}', [\App\Http\Controllers\Acl\RoleController::class, 'edit'])
//        ->middleware(['permission:backend.role.update'])
        ->where('id', '[0-9]+')
        ->name('edit');

    /*
     * Submited form for edition of one Role
     */
    Route::post('/edit/{id}', [\App\Http\Controllers\Acl\RoleController::class, 'update'])
//        ->middleware(['permission:backend.role.update'])
        ->where('id', '[0-9]+')
        ->name('update');

});
