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














    /**
     * Permits to include some routes files using the parameters
     *
     * @param String $dir The path or the dir to the routes directory or the route file to include
     * @param Boolean $recursive : if true try to read the sub directories and try to include routes files.
     * @return void
     */
    public function includeRoutes($dir = null, $recursive = false)
    {
        $path = base_path('routes') . ($dir ? '/' . $dir : '');
        if (is_dir($path) && $handle = opendir($path)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $filename = $path . '/' . $entry;
                    if (is_file($filename) && Tools::isCorrectFileExt($entry, array('php'))) {
                        include_once $filename;
                    } elseif ($recursive) {
                        Tools::includeRoutes(($dir ? '/' . $dir . '/' : '') . $entry, $recursive);
                    }
                }
            }
            closedir($handle);
        } elseif (is_file($path) && file_exists($path) && Tools::isCorrectFileExt($path, array('php'))) {
            include_once $path;
        }
    }




    public function includeModelUrl($model, $field)
    {

        $data = $model::get(['id', $field]);
        $rows = [];
        foreach ($data as $row) {
            $rows[$row->id] = $row->{$field};
        }
        return $rows;
    }





    /**
     * Check if file extension is correct
     *
     * @param string $filename Real filename
     * @param array $authorized_extensions
     * @return bool True if it's correct
     */
    public function isCorrectFileExt($filename, $authorized_extensions = null)
    {
        if ($authorized_extensions === null) {
            $authorized_extensions = array('gif', 'jpg', 'jpeg', 'png');
        }

        $name_explode = explode('.', $filename);

        $count = count($name_explode);
        if ($count >= 2) {
            $current_extension = strtolower($name_explode[$count - 1]);
            if (in_array($current_extension, $authorized_extensions)) {
                return true;
            }
        }

        return false;
    }


}
