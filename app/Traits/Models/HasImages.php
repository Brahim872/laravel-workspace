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

        $old_image = returnUserApi()->avatar;
        $extension = $file->getClientOriginalExtension();
        $path = Storage::disk('public')->putFileAs($path, $file, uniqid() . '.' . $extension);
        $model = $this->getModel();
        $model->update(['avatar' => $path]);


        if (\Storage::exists('public/' . $old_image)) {
            \Storage::delete('public/' . $old_image);
        }

        return returnResponseJson([
            'message' => 'upload success',
        ], Response::HTTP_OK);

    }
}
