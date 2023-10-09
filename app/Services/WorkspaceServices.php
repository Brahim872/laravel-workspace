<?php

namespace App\Services;

use Carbon\Carbon;

class WorkspaceServices
{



    public static function getEndedAtPlan($model)
    {
       return $model->orders
            ->where('status','=','paid')
            ->where('date_end','>=',Carbon::now())
            ->where('order_type','=',"subscribe")
            ->first()->date_end??null;
    }



    public static function getSubscriptionDetails($model)
    {
       return $model->orders
            ->where('status','=','paid')
            ->where('date_end','>=',Carbon::now())
            ->where('order_type','=',"subscribe")
            ->first()??null;
    }



}
