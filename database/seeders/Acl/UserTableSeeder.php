<?php

namespace Database\Seeders\Acl;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

/**
 * UserTableSeeder.
 *
 * Populate database with users examples.
 *
 */
class UserTableSeeder extends Seeder
{

    protected $rows = [
        [
            'id' => 1,
            'name' => 'El fatmi Mohamed',
            'email' => 'admin@email.com',
            'password' => 'admin',
            'role' => 'superadmin',
        ],
        [
            'id' => 2,
            'name' => 'El fatmi Mohamed',
            'email' => 'develop@email.ma',
            'password' => 'admin123',
            'role' => 'user',
        ],



    ];

    public function run()
    {
        foreach ($this->rows as $row) {
            $user = User::find($row['id']);
            $role = $row['role'];
            unset($row['role']);
            if (!$user) {
                $user = new User;
                $user->fill($row);
                $user->save();
            } else {
                $user->update($row);
            }
            $user->roles()->detach();

            $user->assignRole(Role::where('name', '=', $role)->where('guard_name', '=', "sanctum")->get('name')->first()->name);
        }
    }

}
