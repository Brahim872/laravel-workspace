<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;


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

}
