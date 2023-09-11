<?php

namespace App\Helpers;

use App\Models\Contents\Langue;
use getID3;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class Tools
{

    public static $status_code = ['done' => 200,
        'created' => 201,
        'removed' => 204,
        'not_valid' => 400,
        'not_found' => 404,
        'conflict' => 409,
        'permissions' => 401];


    public static function slugify($text)
    {

        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return '/';
        }
        return $text;
    }


    public static function changeFormatDate($dateString, $format = 'd-m-Y H:i:s')
    {// Create a Carbon instance from the date string
        $date = Carbon::parse($dateString);

        $formattedDate = $date->format($format);

        return $formattedDate;

    }


    public function convertDateHD($date)
    {
        $updated_at = strtotime($date);
        $updated_at_date = date('Y-m-d', $updated_at);
        $current_date = date('Y-m-d');

        if ($updated_at_date == $current_date) {
            $time = \Carbon\Carbon::parse($updated_at)->translatedFormat('H:i A');
        } else {
            $time = \Carbon\Carbon::parse($updated_at_date)->translatedFormat('d/m/Y');
        }

        return $time;
    }


    public static function returnResponseJson($data)
    {
        return response()->json([
            "status" => Response::HTTP_BAD_REQUEST,
            "message_code" => Response::$statusTexts[Response::HTTP_BAD_REQUEST],
            "data" => $data,

        ], Response::HTTP_BAD_REQUEST);
    }


}
