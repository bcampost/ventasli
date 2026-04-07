<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MaterialColor;

class MaterialColorSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['type' => 'acero', 'name' => 'Negro', 'sort' => 10],
            ['type' => 'acero', 'name' => 'Blanco', 'sort' => 20],
            ['type' => 'acero', 'name' => 'Grafito', 'sort' => 30],

            ['type' => 'laminado', 'name' => 'Encino', 'sort' => 10],
            ['type' => 'laminado', 'name' => 'Nogal', 'sort' => 20],
            ['type' => 'laminado', 'name' => 'Fresno', 'sort' => 30],
        ];

        foreach ($items as $item) {
            MaterialColor::firstOrCreate(
                ['type' => $item['type'], 'name' => $item['name']],
                ['sort' => $item['sort'], 'is_active' => true]
            );
        }
    }
}