<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Workspace extends Model
{
    use HasFactory, Sluggable;

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
        'name', 'payed_at', 'deactivated_at'
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
}
