<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use App\Traits\LogsActivity;

class Plan extends Model
{
    use HasFactory,
        LogsActivity;

    protected $fillable = [
        "id",
        "name",
        "avatar",
        "price",
        "interval",
        "trial_period_days",
        "lookup_key",
        "st_plan_id",
        "number_app_building",
    ];

    public $incrementing = false;

    public function getActivitylogOptions(): LogOptions
    {
        return (new \Spatie\Activitylog\LogOptions)->logFillable()->logOnlyDirty();
    }

    public function workspaces()
    {
        return $this->hasMany(Workspace::class,'plan_id');
    }
}
