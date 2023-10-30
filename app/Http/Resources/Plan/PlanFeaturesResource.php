<?php

namespace App\Http\Resources\Plan;

use App\Http\Resources\WorkspaceResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanFeaturesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'value' => $this->value,
            'plan_id' => $this->plan_id,
            'key' => $this->key,
            'type' => $this->type,
            'description' => $this->description,
        ];
    }
}
