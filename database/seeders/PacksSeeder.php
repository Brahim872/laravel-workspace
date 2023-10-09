<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\PlanPlusApp;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class PacksSeeder extends Seeder
{


    protected $roles;

    public function __construct()
    {
//        $this->roles = config('pack.items');
    }


    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {


        Plan::whereNotIn('name', ['plan_one','free','one_day','add five app'])->delete();

//        foreach ($this->roles as $index => $role) {
            Plan::updateOrCreate(
                [
                    'id' => 1,
                ],
                [
                    "st_plan_id" => "price_1NwwXsF4001KsKGEs8wYkmif",
                    "name" => "free",
                    "price" => "0",
                    "number_app_building" => "0",
                    "interval" => "month",
                    "is_subscription" => true,
                    "trial_period_days" => "0",
                    "lookup_key" => "free",
                    "updated_at" => "2023-10-01T23:07:02.000000Z",
                    "created_at" => "2023-10-01T23:07:02.000000Z",
                    "id" => 1
                ]
            );

            Plan::updateOrCreate(
                [
                    'id' => 2,
                ],
                [
                    "st_plan_id" => "price_1NwwYwF4001KsKGEwspP7TAz",
                    "name" => "plan_one",
                    "price" => "12",
                    "interval" => "month",
                    "is_subscription" => true,
                    "trial_period_days" => "0",
                    "number_app_building" => "5",
                    "lookup_key" => "plan_one",
                    "updated_at" => "2023-10-01T23:07:02.000000Z",
                    "created_at" => "2023-10-01T23:07:02.000000Z",
                    "id" => 2
                ]
            );

            Plan::updateOrCreate(
                [
                    'id' => 3,
                ],
                [
                    "id" => 3,
                    "st_plan_id" => "price_1NxJvTF4001KsKGEFI16Ma3q",
                    "name" => "one_day",
                    "price" => "5",
                    "interval" => "day",
                    "is_subscription" => true,
                    "trial_period_days" => "0",
                    "number_app_building" => "5",
                    "lookup_key" => "one_day",
                    "updated_at" => "2023-10-01T23:07:02.000000Z",
                    "created_at" => "2023-10-01T23:07:02.000000Z",
                ]
            );

            Plan::updateOrCreate(
                [
                    'id' => 4,
                ],
                [
                    "st_plan_id" => "price_1NwxViF4001KsKGE9AH3lVLh",
                    "name" => "add five app",
                    "price" => "5",
                    "interval" => "day",
                    "number_app_building" => "5",
                    "trial_period_days" => "0",
                    "lookup_key" => "one_day",
                    "is_subscription" => false,
                    "updated_at" => "2023-10-01T23:07:02.000000Z",
                    "created_at" => "2023-10-01T23:07:02.000000Z",
                    "id" => 4
                ]
            );
//        }
    }

}
