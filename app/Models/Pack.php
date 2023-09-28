<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use App\Traits\LogsActivity;

class Pack extends Model
{
    use HasFactory,
        LogsActivity;

    protected $fillable = [
        "id",
        "name",
        "coust",
        "descount",
        "discription"
    ];

    public $incrementing = false;

    public function getActivitylogOptions(): LogOptions
    {
        return (new \Spatie\Activitylog\LogOptions)->logFillable()->logOnlyDirty();
    }
}
