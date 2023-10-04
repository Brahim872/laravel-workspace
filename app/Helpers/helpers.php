<?php


use Illuminate\Http\Response;

if (!function_exists('returnResponseJson')) {
    function returnResponseJson($data, $requestHttp, $key = "data")
    {
        $key = ($key == '' || $key == null) ?'data': $key;

        $response = [
            "status" => $requestHttp,
            "message_code" => Response::$statusTexts[$requestHttp],
        ];

        if (is_array( $data )){
            foreach ($data as $key=>$value){
                $response[$key] = $value;
            }
        }

        return response()->json($response, $requestHttp);
    }

}
if (!function_exists('returnUserApi')) {
    function returnUserApi()
    {
        return auth()->guard('sanctum')->user();
    }

}
