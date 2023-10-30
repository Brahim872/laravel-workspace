<?php

namespace App\Http\Resources\Permission;


use App\Services\WorkspaceServices;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListCategoriesPermissionResource extends JsonResource
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
                'type' => $value->type ?? null,
                'position' => $value->position ?? null,
                'active' => $value->active ?? null,
            ];
        }


        return $result;
    }
}
