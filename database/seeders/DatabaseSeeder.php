<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Otros seeders que ya tengas pueden ir aquí:
        // $this->call(UsersSeeder::class);

        $this->call([
            PriceListMenuSeeder::class,
        ]);
    }
}