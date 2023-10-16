<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
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
        return $this->belongsTo(User::class);
    }

    /**
     * The roles that belong to the user.
     * @param null $typ
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function apps()
    {
        return $this->belongsToMany(Apps::class, 'app_boards','board_id','app_id');
    }

}
