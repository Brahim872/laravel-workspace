<?php

namespace App\Models;

use App\Services\WorkspaceServices;
use App\Traits\Uuid;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use App\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class Workspace extends Model
{
    use LogsActivity;
    use HasFactory, Sluggable,
        SoftDeletes;

//    use Uuid;


    public const ROLE_OWNER = 'owner';
    public const ROLE_MEMBER = 'member';


    protected $guard_name = 'sanctum';

    protected $isActive = true;

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }


    protected $fillable = [
        'name', 'avatar', 'slug', 'deactivated_at', 'plan_id', 'count_app_building'
    ];

    /**
     * The users that belong to the role.
     * @param null $typ
     * @return BelongsToMany
     */
    public function users($typ = null)
    {

        if ($typ != null) {
            return $this->belongsToMany(User::class, 'workspace_user')
                ->withPivot(['type_user'])->wherePivot('type_user', '=', $typ);
        }
        return $this->belongsToMany(User::class, 'workspace_user')
            ->withPivot(['type_user']);
    }


    public function checkIfWorkspaceActive()
    {

        if (!is_null($this->deactivated_at)) {
            return $this->isActive = false;
        }


        if (is_null($this->orders
            ->where('status','=','paid')
            ->where('date_end','>=',Carbon::now())
            ->where('order_type','=',"subscribe")
            ->first())){
            return $this->isActive = false;
        }

        return $this->isActive;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return (new \Spatie\Activitylog\LogOptions)->logFillable()->logOnlyDirty();
    }

    public function plans()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }


    public function orders()
    {
        return $this->hasMany(order::class,'workspace_id');
    }

    public function appBuildings()
    {
        return $this->belongsTo(AppBuilding::class, 'workspace_id');
    }


}
