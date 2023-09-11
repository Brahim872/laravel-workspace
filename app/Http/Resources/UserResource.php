<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $token = null;

        if ($request->route()->uri() == "api/login") {
            $token = $this->createToken('auth-token')->plainTextToken;
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'token' => $token,
            'workspace' => new WorkspaceResource( $this->workspaces((string) User::TYPE_USER['0'])->first(),false),
//            'list_workspaces' => new WorkspaceResource($this->workspaces()->get(),true),
        ];
    }
}
