<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database: the book catalogue and settings,
     * then the admin login from .env.
     */
    public function run(): void
    {
        $this->call([
            CatalogSeeder::class,
            AdminSeeder::class,
        ]);
    }
}
