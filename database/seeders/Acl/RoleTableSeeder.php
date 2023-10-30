<?php

namespace Database\Seeders\Acl;

use Illuminate\Database\Seeder;
use App\Models\Role;

/**
 * RoleTableSeeder.
 *
 * Populate database with users examples.
 *
 */
class RoleTableSeeder extends Seeder
{
    protected $rows = [
        [
            'name' => 'superadmin',
            'display_name' => 'Super Admin',
            'description' => 'Full access',
            'guard_name' => 'sanctum'
        ]
        ,
        [
            'name' => 'user',
            'display_name' => 'User',
            'description' => 'Limited backend access',
            'guard_name' => 'sanctum'
        ]
    ];

    public function run()
    {
        foreach ($this->rows as $data) {
            $role = Role::where("name", "=", $data['name'])->where('guard_name', "=", $data['guard_name'])->first();
            if (!$role) {
                $role = new Role;
                $role->fill($data);
                $role->save();
            } else {
                $role->update($data);
            }
        }
    }
}
