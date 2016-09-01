<?php

use Illuminate\Database\Seeder;

class UsersEquipsSportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
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
            'sport_id' => '1'
        ]);
        DB::table('users_equips_sports')->insert([
            'user_id' => '1',
            'product_id' => '2',
            'sport_id' => '1'
        ]);
        DB::table('users_equips_sports')->insert([
            'user_id' => '1',
            'product_id' => '3',
            'sport_id' => '1'
        ]);
    }
}
