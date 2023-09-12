<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cookie;

class UserResource extends JsonResource
{

    protected $invite, $token;

    public function __construct($resource, /*$invite = false,*/ $token = null)
    {
        parent::__construct($resource);
//        $this->invite = $invite;
        $this->token = $token;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//        $token = null;

//        if ($request->route()->uri() == "api/login" || $this->invite) {
//            $token = $this->createToken('auth-token')->plainTextToken;
//        }
        $_workspace = $this->workspaces()->where('workspaces.id', '=', $this->current_workspace)->first();
        return [
//            'id' => $this->id,
//            'name' => $this->name,
//            'email' => $this->email,
//            'created_at' => $this->created_at,
//            'updated_at' => $this->updated_at,
            'token' => $this->token,
            'workspace' => new WorkspaceResource($_workspace, false, $_workspace->pivot->type_user),
        ];
    }
}
