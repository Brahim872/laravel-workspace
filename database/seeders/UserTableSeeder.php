<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Traits\Date;
use Illuminate\Database\Seeder;
Use \Carbon\Carbon;

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
            'first_name' => 'admin',
            'last_name' => 'admin',
            'email' => 'admin@email.com',
            'password' => 'admin',

//            'role' => 'superadmin',
        ],


    ];

    public function run()
    {
        foreach ($this->rows as $row) {
            $user = User::find($row['id']);
//            $role = $row['role'];
//            unset($row['role']);
            if (!$user) {
                $user = new User;
                $user->fill($row);
                $user->save();
            } else {
                $user->update($row);
            }

            $user->update(['email_verified_at' =>   Carbon::now()]);
//            $user->roles()->detach();

//            $user->assignRole(Role::where('name', '=', $role)->where('guard_name', '=', "web")->get('name')->first()->name);
        }
    }

}
