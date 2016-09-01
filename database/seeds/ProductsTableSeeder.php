<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            'ean' => 'admin',
            'name' => 'Haltere',
            'description' => 'Haltere souple',
            'picture' => '/default_picture/default-equipement.jpg',
            'price' => 40,
            'url' => "http://www.fnac.com/mp26613509/Body-one-halteres-poignets-chevilles-2-x-2kg/w-4?ectrans=1&gclid=CjwKEAjwtqe8BRCs-9DdpMOilBoSJAAyqWz_35PUvahHE7tBNlmZhD1OtL7xoa-FEKi5DjQvCld4BoCadzw_wcB&mckv=lSI5HSi4_dc&oref=90999964-51c3-5033-4764-8c95e99ce66e&Origin=CMP_GOOGLE_MP_SPORT&pcrid=77401522223",
            'brand_id' => 1,
            'category_id' => 1,
            'user_id' => 1,
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
        DB::table('products')->insert([
            'ean' => 'admin',
            'name' => 'Muscle poids',
            'description' => 'Muscle poignet',
            'picture' => '/default_picture/default-equipement.jpg',
            'price' => 25,
            'url' => "http://www.rueducommerce.fr/m/ps/mpid:MP-CC349M19863952#moid:MO-CC349M32132418",
            'brand_id' => 1,
            'category_id' => 1,
            'user_id' => 1,
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
        DB::table('products')->insert([
            'ean' => 'admin',
            'name' => 'Tapis',
            'description' => 'Tapis course',
            'picture' => '/default_picture/default-equipement.jpg',
            'price' => 2,
            'url' => "http://www.fitnessboutique.fr/dynamic/page/catalogue/product.aspx?ref=FIT01072&aff=googleShopping-famA4&utm_source=googleShopping&utm_medium=catalog&utm_term=A4&utm_campaign=googleShopping&xtor=AL-23&filedate=20160716&source=pla&gclid=CjwKEAjwtqe8BRCs-9DdpMOilBoSJAAyqWz__sOKvFRWdwV3qzLEJp8cZ5KSAVYEq74elnqQZkwdnRoCZMvw_wcB",
            'brand_id' => 2,
            'category_id' => 2,
            'user_id' => 1,
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);

    }
}
