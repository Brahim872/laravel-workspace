<?php

namespace Database\Seeders;




use Database\Seeders\Acl\CategoryPermissionsTableSeeder;
use Database\Seeders\Acl\CreatePermissionsTableSeeder;
use Database\Seeders\Acl\RoleTableSeeder;
use Database\Seeders\Acl\UserTableSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleTableSeeder::class);
        $this->call(CategoryPermissionsTableSeeder::class);
        $this->call(CreatePermissionsTableSeeder::class);
        $this->call(UserTableSeeder::class);

//        $this->call(AppsSeeder::class);
//        $this->call(PacksSeeder::class);

    }
}
