<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        /*kung i type mo yung php artisan db:seed ma seed na lahat ng seeder class. dagdagan lang natin ito soon*/
        $this->call([
            AdminUserSeeder::class,
            CustomerUserSeeder::class,
            CategorySeeder::class,
            ProductsTableSeeder::class,
            CartSeeder::class,
            OrderSeeder::class,
            AnalyticsDemoSeeder::class,
        ]);
    }
}
