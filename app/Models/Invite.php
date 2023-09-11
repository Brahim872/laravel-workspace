<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Invite extends Model
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'email', 'token', 'registered_at', 'workspace'
    ];


    public function generateInvitationToken()
    {
        $this->token = substr(md5(rand(0, 9) . $this->email . time()), 0, 32);
    }

    public function getLink()
    {
        return urldecode(env('FRONTEND_URL') . '/register' . '?token=' . $this->token . 'email=' . $this->email);
    }

    public function hasAleardyInvitation()
    {
        return Invite::where('email', '=', $this->email)->where('workspace', '=', $this->workspace)->count() > 0 ? true : false;
    }

}
