<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvitationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $token = null;

//        if ($request->route()->uri() == "api/login") {
//            $token = $this->createToken('auth-token')->plainTextToken;
//        }
        return [
            'id' => $this->id,
            'email' => $this->email,
            'token' => $this->token,
            'workspace' => $this->workspace,

        ];
    }
}
