<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppBoard extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'is_public',
    ];


    /**
     * Get the comments for the blog post.
     */

    public function users()

    {
        return $this->hasMany(User::class);
    }

}
