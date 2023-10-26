<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apps extends Model
{
    use HasFactory;

    protected $fillable = [
      'name',
      'type'
    ];
    protected $table = "apps";

    /**
     * The roles that belong to the user.
     * @param null $typ
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function boards()
    {
        return $this->belongsToMany(Board::class, 'app_boards','board_id','app_id');
    }



}
