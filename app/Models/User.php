<?php

namespace App\Models;

use App\Traits\HasImages;
use App\Traits\HasWorkspace;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\CausesActivity;
use App\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{

    use HasApiTokens,
        HasFactory,
        Notifiable,
        HasWorkspace,
        HasRoles,
        HasImages,
        LogsActivity,
        CausesActivity,
        SoftDeletes;

    protected $guard_name = 'sanctum';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'current_workspace',
        'ip_address',
        'device',
        'avatar',
        'is_email_verified',
        'social_id',
        'social_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    /**
     * get Current workspace.
     */
    public function getCurrentWorkspace()
    {
        return Workspace::find($this->current_workspace);
    }


    /**
     * The roles that belong to the user.
     */
    public function hasWorkspaces()
    {
        return $this->workspaces()->get();
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->ip_address = request()->ip();
            $user->device = request()->header('User-Agent');
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return (new \Spatie\Activitylog\LogOptions)->logFillable()->logOnlyDirty();
    }


    public function boards()
    {
        return $this->hasMany(Board::class)->orderByDesc('created_at');
    }



    /**
     * @var mixed
     */

    /**
     * The roles that belong to the user.
     * @param null $typ
     * @return BelongsToMany
     */
    public function workspaces($typ = null)
    {
        if ($typ != null) {
            return $this->belongsToMany(Workspace::class, 'workspace_user')
                ->withPivot(['type_user'])->wherePivot('type_user', '=', $typ);
        }

        return $this->belongsToMany(Workspace::class, 'workspace_user')->withPivot(['type_user']);
    }
}
