<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkspaceResource extends JsonResource
{

    /**
     * @var boolean
     */
    protected $isCollect;

    /**
     * @var int
     */
    private $typeUser;

    public function __construct($resource, $isCollect = false, $typeUser = 0)
    {
        parent::__construct($resource);
        $this->isCollect = $isCollect;
        $this->typeUser = $typeUser;
    }


    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $result = [];


        if ($this->isCollect == true) {

            foreach ($this->resource as $key => $workspace) {

                $result[] = [
                    'id' => $workspace->id,
                    'slug' => $workspace->slug,
                    'name' => $workspace->name,
                    'paid_at' => $workspace->paid_at,
                    'type_user' => User::TYPE_USER[$workspace->pivot->type_user],
                ];
            }
        } else {

            $result = [
                'id' => $this->id,
//                'slug' => $this->slug,
                'name' => $this->name,
                'paid_at' => $this->paid_at,
                'type_user' => User::TYPE_USER[$this->typeUser],
//                'role' => new RoleResource($this->roles->first()),
                'role' => $this->roles->first()->name,
            ];

        }
        return $result;
    }
}
