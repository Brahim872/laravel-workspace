<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory,
        SoftDeletes;
//    use Uuid;

//    /**
//     * @var mixed
//     */
//    public $user_id;
//    /**
//     * @var mixed
//     */
//    public $workspace_id;
//    /**
//     * @var mixed|string
//     */
//    public $session_id;
//    /**
//     * @var mixed|string
//     */
//    public $order_type;
//    /**
//     * @var mixed
//     */
//    public $total_price;
//    /**
//     * @var mixed|string
//     */
//    public $status;





    const TYPEPLAN = [
        'subscribe'=>'subscribe',
        'purchase'=>'purchase',
    ];

    protected $fillable = [
        'status',
        'total_price',
        'order_type',
        'session_id',
        'workspace_id',
        'user_id',
        'details',
        'payment_id',
    ];
}
