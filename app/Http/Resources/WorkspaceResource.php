<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkspaceResource extends JsonResource
{

    protected $isCollect;

    public function __construct($resource, $isCollect = false)
    {
        parent::__construct($resource);
        $this->isCollect = $isCollect;
    }


    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @param bool $isCollect
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
                    'type_user' => User::TYPE_USER[$workspace->pivot->type_user],
//                    'created_at' => $workspace->created_at,
//                    'payed_at' => $workspace->payed_at,
//                    'deactivated_at' => $workspace->deactivated_at,

                ];
            }
        } else {
            $typeUser = $this->users->first()->pivot->type_user;
            $result = [
                'id' => $this->id,
                'slug' => $this->slug,
                'name' => $this->name,
                'type_user' => User::TYPE_USER[$typeUser],
//                'created_at' => $this->created_at,
//                'payed_at' => $this->payed_at,
//                'deactivated_at' => $this->deactivated_at,

            ];
        }
        return $result;
    }
}
