<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{


    protected $roles;

    public function __construct()
    {
        $this->roles = config('app-sittings.roles');
    }




    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
//        Role::query()->delete();

        $myRoles = [];
        foreach ($this->roles as $index => $role) {
            $myRoles[] = $role['name'];
        }

        Role::whereNotIn('name',$myRoles)->delete();

        foreach ($this->roles as $index => $role) {
            Role::updateOrCreate(
                [
                    'id' => $role['id'],
                    'name' => $role['name']
                ],
                [
                    'id' => $role['id'],
                    'guard_name' => 'sanctum',
                    'display_name' => $role['display_name']??$role['name'],
                    'name' => $role['name'],
                    'description' => $role['description']??'---',
                ]
            );
        }
    }

}
