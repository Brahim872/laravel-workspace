<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\LogOptions;
use App\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class Workspace extends Model
{
    use LogsActivity;
    use HasFactory, Sluggable, HasRoles;


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
        'name', 'paid_at', 'deactivated_at'
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



    public function getActivitylogOptions(): LogOptions
    {
        return (new \Spatie\Activitylog\LogOptions)->logFillable()->logOnlyDirty();
    }
}
