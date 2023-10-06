<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppBuilding extends Model
{
    use SoftDeletes,HasFactory;


    protected $fillable = [
        'name',
        'slug',
        'workspace_id',
        'user_id',
    ];



    public function workspace()
    {
        return $this->belongsTo(Workspace::class, 'workspace_id');
    }

}
