<?php

namespace App\Models;

use App\Traits\HasImages;
use App\Traits\HasWorkspace;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{

    use HasApiTokens,
        HasFactory,
        Notifiable,
        HasWorkspace,
        HasRoles,
        HasImages;

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

    const TYPE_USER = [
        0 => "admin",
        1 => "invite",
    ];
    /**
     * @var mixed
     */

    /**
     * The roles that belong to the user.
     * @param null $typ
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function workspaces($typ = null)
    {
        if ($typ != null) {
            return $this->belongsToMany(Workspace::class, 'workspace_user')
                ->withPivot(['type_user'])->wherePivot('type_user', '=', (int)$typ);
        }

        return $this->belongsToMany(Workspace::class, 'workspace_user')->withPivot(['type_user']);
    }


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


}
