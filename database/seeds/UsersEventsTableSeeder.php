<?php

use Illuminate\Database\Seeder;

class UsersEventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users_events')->insert([
            'user_id' => '1',
            'event_id' => '1',
            'is_admin' => '1'
        ]);
        DB::table('users_events')->insert([
            'user_id' => '2',
            'event_id' => '1',
            'is_admin' => '0'
        ]);
        DB::table('users_events')->insert([
            'user_id' => '1',
            'event_id' => '2',
            'is_admin' => '1'
        ]);
        DB::table('users_events')->insert([
            'user_id' => '3',
            'event_id' => '2',
            'is_admin' => '0'
        ]);
    }
}
