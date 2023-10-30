<?php

namespace Database\Seeders\Acl;

use App\Models\CategoryPermission;
use Illuminate\Database\Seeder;

class CategoryPermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $position = 0;
        foreach ($this->datas as $data) {
            $position++;
            $data['position'] = $position;
            $category = CategoryPermission::where("id", "=", $data['id'])->first();
            if (!$category) {
                $category = new CategoryPermission;
                $category->fill($data);
                $category->save();
            } else {
                $category->update($data);
            }
        }
    }

    protected $datas = [

        [
            'id' => '1',
            'name' => 'backend_generality',
            'display_name' => 'Généralités',
            'position' => '1',
            'type' => '0',
        ],
        [
            'id' => '2',
            'name' => 'backend_user',
            'display_name' => 'Utilisateur CRM',
            'position' => '2',
            'type' => '0',
        ],

        [
            'id' => '3',
            'name' => 'backend_role',
            'display_name' => 'Rôle',
            'position' => '3',
            'type' => '0',
        ],

        [
            'id' => '4',
            'name' => 'backend_permission',
            'display_name' => 'Permission',
            'position' => '4',
            'type' => '0',
        ],

    ];
}
