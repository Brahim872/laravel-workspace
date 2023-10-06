<?php

namespace App\Models;

use App\Traits\Validatorable;

class Role extends \Spatie\Permission\Models\Role
{

    use Validatorable;


    /**
     * The attributes that are mass assignable.
     *
     * @var  array
     */
    protected $fillable = ['name', 'display_name', 'description','guard_name'];

    /**
     * Validation rules
     *
     * @return  array
     */
    public function rules()
    {
        $exceptId = $this->id ? ',' . $this->id : '';

        return [
            'name' => 'required|max:255|unique:roles,name' . $exceptId,
            'guard_name' => 'max:255',
            'display_name' => 'required|max:255|unique:roles,display_name' . $exceptId,
            'description' => 'max:255',
        ];
    }


}
