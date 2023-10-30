<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanFeatures extends model
{

    protected $fillable = ['key', 'value', 'type', 'plan_id'];

    protected $table = "plan_features";


    public function plan()
    {
        return $this->belongsTo(Plan::class,'plans','plan_id');
    }


}
