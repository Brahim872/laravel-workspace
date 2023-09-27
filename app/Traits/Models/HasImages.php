<?php

namespace App\Traits\Models;

use App\Models\Image;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;

use Illuminate\Support\Facades\Storage;
use function Termwind\ValueObjects\lowercase;


trait HasImages
{


    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }


    public function addImage(UploadedFile $file, $folder = 'images', $disk = 'public')
    {
        try {

            $extension = $file->getClientOriginalExtension();
            $path = Storage::disk($disk)->putFileAs($folder, $file, uniqid() . '.' . $extension);

            $model = $this->getModel();
            $image = new Image;
            $image['url'] = $path;
            $model->images()->save($image);

            return returnResponseJson([
                'message' => 'upload success',
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return returnResponseJson([
                'message' => $e->getMessage(),
                'getCode' => $e->getCode(),
            ], 500);
        }
    }



    public function updateImage(UploadedFile $file, $folder = 'images', $disk = 'public')
    {
        try {

            $extension = $file->getClientOriginalExtension();
            $path = Storage::disk($disk)->putFileAs($folder, $file, uniqid() . '.' . $extension);

            $model = $this->getModel();
            $image = new Image;
            $image['url'] = $path;
            $model->images()->save($image);

            dd($model);


        } catch (\Exception $e) {
            return returnResponseJson([
                'message' => $e->getMessage(),
                'getCode' => $e->getCode(),
            ], 500);
        }
    }



    public function changeAvatar($file, $path = 'images')
    {

        $image = $file;
        $input['avatar'] = uniqid() . '.' . $image->getClientOriginalExtension();

        $destinationPath = storage_path('public/'.$path);


        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true); // The third parameter ensures nested directories are created
        }

        $imgFile = (new \Intervention\Image\ImageManager)->make($image->getRealPath());

        $imgFile->resize(150,150)
            ->save($destinationPath . '/' . $input['avatar'],60);

        return returnResponseJson([
            'message' => 'upload success',
        ], Response::HTTP_OK);

    }
}
