<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;

class PlanPlusApp extends Model
{
    use HasFactory,
        LogsActivity,
        SoftDeletes;

    protected $fillable = [
        "id",
        "name",
        "avatar",
        "price",
        "st_plan_id",
        "number_app_building",
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return (new \Spatie\Activitylog\LogOptions)->logFillable()->logOnlyDirty();
    }


}
