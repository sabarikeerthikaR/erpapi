<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vehicle_types')->insert([[
            'id' => 1,
            'name' => "Micro",
            'icon' => "Micro_icon",
            'base_fare' =>1,
            'rate_per_km' =>5,
            'per_minute_wait_charge'=>1,
            'capacity'=>3
        ],
            [
                'id' => 2,
                'name' => "Mini",
                'icon' => "Mini_icon",
                'base_fare' => 1,
                'rate_per_km' =>6,
                'per_minute_wait_charge'=>1,
                'capacity'=>3
            ],
            [
                'id' => 3,
                'name' => "sedan",
                'icon' => "sedan_icon",
                'base_fare' =>2,
                'rate_per_km' =>7,
                'per_minute_wait_charge'=>2,
                'capacity'=>4
            ],
            [
                'id' => 4,
                'name' => "prime sedan",
                'icon' => "prime_sedan_icon",
                'base_fare' =>2,
                'rate_per_km' =>8,
                'per_minute_wait_charge'=>2,
                'capacity'=>4
            ]]);
    }
}
