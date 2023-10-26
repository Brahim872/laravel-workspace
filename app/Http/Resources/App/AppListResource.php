<?php


namespace App\Http\Resources\App;


use App\Models\Apps;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class AppListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $result = [];

        foreach ($this->resource as $key => $app) {
            $result[] = [
                'id' => $app->id,
                'name' => $app->name ?? null,
                'type' => $app->type ?? null,
                'inBoard' => $app->boards->count()>0 ?? false,
            ];
        }


        return $result;
    }
}
