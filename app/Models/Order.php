<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

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
        'plan'=>'plan',
        'add_apps'=>'add apps',
    ];

    protected $fillable = [
        'status',
        'total_price',
        'plan_type',
        'session_id',
        'workspace_id',
        'user_id',
        'payment_id',
    ];
}
