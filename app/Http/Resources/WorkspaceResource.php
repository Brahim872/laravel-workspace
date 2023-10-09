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
        $isActive = false;

        if ($this->isCollect == true) {

            foreach ($this->resource as $key => $workspace) {
                $result[] = [
                    'id' => $workspace->id,
                    'slug' => $workspace->slug,
                    'name' => $workspace->name ?? null,
                    'type_user' => User::TYPE_USER[$workspace->pivot->type_user],
                ];
            }
        } else {

            if (
                is_null($this->deactivated_at)
                && !is_null($this->plan_id)
                && !is_null($this->payment_id)
                && (!is_null($this->count_app_building) || $this->count_app_building == 0)
            ) {
                $isActive = true;
            }

            $result = [
                'id' => $this->id,
                'slug' => $this->slug,
                'name' => $this->name,
                'count_app_building' => $this->count_app_building,
                'type_user' => User::TYPE_USER[$this->typeUser],
                'plan' => $this->plans()->first()->name ?? null,
                'active' => $isActive,
            ];
        }
        return $result;
    }
}
