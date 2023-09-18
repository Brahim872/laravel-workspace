<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\HasWorkspace;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{

    use HasApiTokens, HasFactory, Notifiable, HasWorkspace, HasRoles;

    protected $guard_name = 'sanctum';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'current_workspace',
        'ip_address',
        'device',
        'is_email_verified',
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
            // Store the user's IP address
            $user->ip_address = request()->ip();

            // Store the user's device information
            $user->device = request()->header('User-Agent');
        });
    }

}
