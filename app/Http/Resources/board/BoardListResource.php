<?php

namespace App\Http\Resources\Board;


use App\Services\WorkspaceServices;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BoardListResource extends JsonResource
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
                'user_id' => $value->user_id,
                'name' => $value->name ?? null,
            ];
        }


        return $result;
    }
}
