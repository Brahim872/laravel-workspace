<?php

namespace Database\Seeders;

use App\Models\Apps;
use Illuminate\Database\Seeder;

class AppsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Apps::factory()->count(50)->create();
    }

}
