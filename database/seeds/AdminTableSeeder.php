<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
           'first_name' => 'drupp',
            'last_name' => 'admin',
            'email' => 'admin@drupp.com',
            'password' => Hash::make('Drupp@123Nigeria!'),
            'country_code' => 234,
            'phone' => "0987654321",
            'latitude' => "9.0820",
            'longitude' => "8.6753",
            'type' => 3
        ]);
    }
}
