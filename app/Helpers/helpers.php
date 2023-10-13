<?php


use App\Jobs\BackupDatabase;
use Illuminate\Http\Response;

if (!function_exists('returnResponseJson')) {
    function returnResponseJson($data, $requestHttp, $key = "data")
    {
        $key = ($key == '' || $key == null) ? 'data' : $key;

        $response = [
            "status" => $requestHttp,
            "message_code" => Response::$statusTexts[$requestHttp],
        ];

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $response[$key] = $value;
            }
        }

if (config("backup.status"))
        dispatch(new BackupDatabase());


        return response()->json($response, $requestHttp);
    }

}


if (!function_exists('returnCatchException')) {
    function returnCatchException($e)
    {


        if (config("backup.status"))
            dispatch(new BackupDatabase());


        return returnResponseJson([
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ], 500);
    }

}


if (!function_exists('returnWarningsResponse')) {
    function returnWarningsResponse($e)
    {

        if (config("backup.status"))
            dispatch(new BackupDatabase());

        return returnResponseJson(["Warnings" => $e], Response::HTTP_CONFLICT);

    }

}


if (!function_exists('returnValidatorFails')) {
    function returnValidatorFails($e)
    {

        if (config("backup.status"))
            dispatch(new BackupDatabase());

        if (is_object($e) && method_exists($e, 'messages')) {
            return returnResponseJson(["errors" => $e->messages()], Response::HTTP_BAD_REQUEST);
        } else {
            return returnResponseJson(["errors" => $e], Response::HTTP_BAD_REQUEST);
        }
    }

}


if (!function_exists('returnUserApi')) {
    function returnUserApi()
    {
        return auth()->guard('sanctum')->user();
    }
}
