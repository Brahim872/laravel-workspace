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

        foreach ($this->resource as $key => $board) {
            $result[] = [
                'id' => $board->id,
                'user_id' => $board->user_id,
                'name' => $board->name ?? null,
            ];
        }


        return $result;
    }
}
