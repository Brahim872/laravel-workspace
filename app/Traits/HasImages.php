<?php

namespace App\Traits;

use App\Models\Image;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;

use Illuminate\Support\Facades\Storage;
use function Ramsey\Uuid\Generator\timestamp;
use function Ramsey\Uuid\setTimeProvider;
use function Termwind\ValueObjects\lowercase;
use Intervention\Image\ImageManagerStatic as InterventionImage;


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
        $input['avatar'] = returnUserApi()->id.'-'.today()->timestamp.'-'.uniqid() . '.' . $image->getClientOriginalExtension();

        $destinationPath = storage_path('app/public/'.$path);

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true); // The third parameter ensures nested directories are created
        }
        $pathUrl = $path.'/'.$input['avatar'];
        $imgFile = (new \Intervention\Image\ImageManager)->make($image->getRealPath());

        $imgFile->resize(150,150)
            ->save($destinationPath . '/' . $input['avatar'],60);

        $model = $this->getModel();
        $model->update(['avatar' => $pathUrl]);

        return returnResponseJson([
            'message' => 'upload success',
        ], Response::HTTP_OK);

    }
}
