<?php

use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('events')->insert([
            'name' => 'Roland Garros',
            'picture' => '/images/event1.jpg',
            'address' => 'Avenue Gordon Bennett',
            'number_street' => '2bis',
            'city_code' => '75016',
            'city' => 'Paris',
            'country' => 'France',
            'longitude' => 2.246489600000018,
            'lattitude' => 48.8473142,
            'user_id' => 1,
            'sport_id' => 13,
            'started_at' => Carbon\Carbon::now(),
            'end_at' => Carbon\Carbon::now(),
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);

        DB::table('events')->insert([
            'name' => 'Finale de l\'euro',
            'picture' => '/images/event2.jpg',
            'address' => 'Stade de France',
            'number_street' => '',
            'city_code' => '93216 ',
            'city' => 'Saint-Denis',
            'country' => 'France',
            'longitude' => 2.3601644999999962,
            'lattitude' => 48.9244592,
            'user_id' => 1,
            'sport_id' => 1,
            'started_at' => Carbon\Carbon::now(),
            'end_at' => Carbon\Carbon::now(),
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
    }
}
