<?php


use Illuminate\Http\Response;

if (!function_exists('returnResponseJson')) {
    function returnResponseJson($data,$requestHttp)
    {
        return response()->json([
            "status" => $requestHttp,
            "message_code" => Response::$statusTexts[$requestHttp],
            "data" => $data,

        ], $requestHttp);
    }

}
if (!function_exists('returnUserApi')) {
    function returnUserApi()
    {
        return auth()->guard('sanctum')->user();
    }

}
