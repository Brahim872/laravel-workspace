<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $_workspace = $this->workspaces()->where('workspaces.id', '=', $this->current_workspace)->first();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'role' => $this->roles->first()->name,
            'workspace' => $_workspace?new WorkspaceResource($_workspace, false, $_workspace->pivot->type_user):null,
        ];
    }
}
