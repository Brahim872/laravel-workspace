<?php

namespace Database\Seeders;

use App\Models\Role;
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
            'name' => 'admin admin',
            'email' => 'admin@email.com',
            'password' => 'admin',
        ],


    ];

    public function run()
    {
        foreach ($this->rows as $row) {
            $user = User::find($row['id']);

            if (!$user) {
                $user = new User;
                $user->fill($row);
                $user->save();
            } else {
                $user->update($row);
            }

            $user->update(['email_verified_at' =>   Carbon::now()]);

            $user->roles()->detach();
            $user->assignRole('user');
        }
    }

}
