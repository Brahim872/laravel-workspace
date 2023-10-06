<?php

namespace App\Models;

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


    protected $guard_name = 'sanctum';


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
        'name', 'avatar', 'slug', 'deactivated_at', 'payment_id', 'plan_id', 'count_app_building'
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
                ->withPivot(['type_user'])->wherePivot('type_user', '=', (int)$typ);
        }
        return $this->belongsToMany(User::class, 'workspace_user')
            ->withPivot(['type_user']);
    }


    /**
     * The users that belong to the role.
     * @param null $typ
     * @return BelongsToMany
     */
    public function planPlusApps()
    {
        return $this->belongsToMany(PlanPlusApp::class, 'workspace_plan_plus_apps');
    }


    public function getActivitylogOptions(): LogOptions
    {
        return (new \Spatie\Activitylog\LogOptions)->logFillable()->logOnlyDirty();
    }

    public function plans()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function appBuildings()
    {
        return $this->belongsTo(AppBuilding::class, 'workspace_id');
    }

}
