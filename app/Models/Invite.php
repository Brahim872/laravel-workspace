<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use App\Traits\LogsActivity;

class Invite extends Model
{
    use HasFactory;
    use Notifiable,
        SoftDeletes;

    use LogsActivity;


    protected $fillable = [
        'email', 'token', 'accepted_at', 'workspace'
    ];


    public function generateInvitationToken()
    {
        $this->token = substr(md5(rand(0, 9) . $this->email . time()), 0, 32);
    }

    public function getLink()
    {

        if (User::where('email', '=', $this->email)->first()) {
            return urldecode(env('FRONTEND_URL') . '/accept-invitation' . '?token=' . $this->token . '&email=' . $this->email);
        }

        return urldecode(env('FRONTEND_URL') . '/register' . '?token=' . $this->token . '&email=' . $this->email);
    }

    public function hasAleardyInvitation()
    {
        return Invite::where('email', '=', $this->email)->where('workspace', '=', $this->workspace)->count() > 0 ? true : false;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return (new \Spatie\Activitylog\LogOptions)->logFillable()->logOnlyDirty();
    }


}
