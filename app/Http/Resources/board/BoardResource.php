<?php

namespace App\Http\Resources\Board;


use App\Http\Resources\App\AppListResource;
use App\Services\WorkspaceServices;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BoardResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
            return [
                'id' => $this->id,
                'user_id' => $this->user_id,
                'name' => $this->name ?? null,
                'apps' => new AppListResource($this->apps) ?? null,
            ];

    }
}
