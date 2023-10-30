<?php

namespace App\Http\Resources\Role;


use App\Services\WorkspaceServices;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListRoleResource extends JsonResource
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
                'display_name' => $value->display_name ?? null,
                'description' => $value->description ?? null,
                'created_at' => $value->created_at ?? null,
            ];
        }


        return $result;
    }
}
