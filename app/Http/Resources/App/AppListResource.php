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

        foreach ($this->resource as $key => $value) {
            $result[] = [
                'id' => $value->id,
                'name' => $value->name ?? null,
                'type' => $value->type ?? null,
                'inBoard' => $value->boards->count()>0 ?? false,
            ];
        }


        return $result;
    }
}
