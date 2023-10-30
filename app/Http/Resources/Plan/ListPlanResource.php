<?php

namespace App\Http\Resources\Plan;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListPlanResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $result = [];

        foreach ($this->resource as $key => $value) {
            $result[] = [
                'id' => $value->id,
                'name' => $value->name,
                'created_at' => $value->created_at ?? null,
            ];
        }


        return $result;
    }
}
