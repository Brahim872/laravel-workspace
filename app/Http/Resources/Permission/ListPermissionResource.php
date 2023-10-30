<?php

namespace App\Http\Resources\Permission;


use App\Services\WorkspaceServices;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListPermissionResource extends JsonResource
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
                'guard_name' => $value->display_name ?? null,
                'display_name' => $value->display_name ?? null,
                'description' => $value->description ?? null,
                'category_id' => $value->category_id ?? null,
                'position' => $value->position ?? null,
            ];
        }


        return $result;
    }
}
