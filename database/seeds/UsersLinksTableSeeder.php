<?php

use Illuminate\Database\Seeder;

class UsersEquipsSports extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users_equips_sports')->insert([
            'user_id' => '1',
            'product_id' => '1',
            'sport_id' => '1',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
        DB::table('users_equips_sports')->insert([
            'user_id' => '1',
            'product_id' => '2',
            'sport_id' => '1',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
        DB::table('users_equips_sports')->insert([
            'user_id' => '1',
            'product_id' => '3',
            'sport_id' => '1',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
    }
}
