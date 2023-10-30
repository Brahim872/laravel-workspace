<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use HasFactory,
        SoftDeletes;


    protected $table = "subscribers";


    const TYPEPLAN = [
        'subscribe' => 'subscribe',
        'purchase' => 'purchase',
    ];

    protected $fillable = [
        'workspace_id',
        'user_id',
        'st_total_price',
        'st_session_id',
        'st_cus_id',
        'st_sub_id',
        'st_cus_id',
        'st_end_at',
        'st_payment_method',
        'st_payment_status',
        'unsubscribed_at',
        'unsubscribe_event_id',
    ];


    public function workspace()
    {
        return $this->belongsTo(Workspace::class, 'workspace_id');
    }


//    public function payments()
//    {
//        return $this->hasMany(Payment::class,'order_id');
//    }

}
