<?php

namespace Database\Seeders;

use App\Models\Pack;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class PacksSeeder extends Seeder
{


    protected $roles;

    public function __construct()
    {
        $this->roles = config('pack.items');
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

        Pack::whereNotIn('name',$myRoles)->delete();

        foreach ($this->roles as $index => $role) {
            Pack::updateOrCreate(
                [
                    'id' => $role['id'],
                    'name' => $role['name']
                ],
                [
                    'id' => $role['id'],
                    'name' => $role['name'],
                    'coust' => $role['coust'],
                    'descount' => $role['descount'],
                    'discription' => $role['discription'],
                ]
            );
        }
    }

}
