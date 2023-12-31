<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory,
        SoftDeletes;
//    use Uuid;

    protected $fillable = [
        'total',
        'order_id',
        'st_cus_id',
        'st_sub_id',
        'st_plan_id',
        'st_payment_intent_id',
        'st_payment_method',
        'st_payment_status',
        'date',
    ];




    public function orders()
    {
        return $this->belongsTo(Subscription::class, 'workspace_id');
    }

}
