<?php

namespace App\Models;



class Permission extends  \Spatie\Permission\Models\Permission
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'display_name', 'active', 'category_id'];

    /**
     * Disable Created_by and Updated_by
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Validation rules
     *
     * @return array
     */
    public function rules()
    {
        $exceptId = $this->id ? ',' . $this->id : '';

        return [
            'name' => 'required|max:255|unique:permissions,name' . $exceptId,
            'display_name' => 'required|max:255|unique:permissions,display_name' . $exceptId
        ];
    }


}
