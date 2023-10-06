<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryPermission extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'categories_permissions';
    /**
     * Disable Created_by and Updated_by
     *
     * @var boolean
     */
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'display_name', 'type', 'position', 'active'];

    /**
     * Validation rules
     *
     * @return array
     */
    public function rules()
    {
        $exceptId = $this->id ? ',' . $this->id : '';

        return [
            'name' => 'required|max:255|unique:categories_permissions,name' . $exceptId,
            'display_name' => 'required|max:255|unique:categories_permissions,display_name' . $exceptId,
            'type' => 'required',
        ];
    }

    public function permissions()
    {
        return $this->hasMany('App\Models\Acl\Permission', 'category_id')->orderBy('position');
    }
}
