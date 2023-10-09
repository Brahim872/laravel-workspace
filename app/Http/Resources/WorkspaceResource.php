<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\Workspace;
use App\Services\WorkspaceServices;
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

    public function __construct($resource, $isCollect = false, $typeUser = "owner")
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
                    'name' => $workspace->name ?? null,
                    'type_user' => $workspace->pivot->type_user,
                ];
            }
        } else {
            $result = [
                'id' => $this->id,
                'slug' => $this->slug,
                'name' => $this->name,
                'count_app_building' => $this->count_app_building,
                'type_user' => $this->typeUser,
                'plan' => $this->plans()->first()->name ?? null,
                'end_at' => WorkspaceServices::getEndedAtPlan($this),
                'active' => $this->checkIfWorkspaceActive(),
            ];
        }
        return $result;
    }
}
