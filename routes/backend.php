<?php

use App\Http\Controllers\App\AppsController;
use App\Http\Controllers\PlanController;
use Symfony\Component\HttpFoundation\Response;

Route::fallback(function () {
    return returnResponseJson([
        'message' => '404 | not found.'
    ], Response::HTTP_NOT_FOUND);
});


(new App\Helpers\Tools)->includeRoutes('backend');
