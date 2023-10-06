<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use App\Traits\LogsActivity;

class Image extends Model
{
    use HasFactory,
        LogsActivity,
        SoftDeletes;


    protected $fillable = [
        'url'
    ];


    /**
     * Get all of the owning commentable models.
     */
    public function imageable()
    {
        return $this->morphTo();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return (new \Spatie\Activitylog\LogOptions)->logFillable()->logOnlyDirty();
    }

}
