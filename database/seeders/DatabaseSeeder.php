<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // otros seeders...
        $this->call([
            MenuNodesSeeder::class,
        ]);
    }
}