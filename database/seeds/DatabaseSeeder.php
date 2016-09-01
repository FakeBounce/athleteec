<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersLinksTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(SportsTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        $this->call(AssociationsTableSeeder::class);
        $this->call(EventsTableSeeder::class);
        $this->call(PublicationsTableSeeder::class);
        $this->call(ActivitiesTableSeeder::class);
        $this->call(UsersEquipsSportsTableSeeder::class);
        $this->call(BrandsTableSeeder::class);




    }
}


