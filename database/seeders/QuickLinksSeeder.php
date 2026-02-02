<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuickLink;

class QuickLinksSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'HOME', 'url' => '/home', 'icon' => 'home', 'sort_order' => 10],
            ['name' => 'Proyectos', 'url' => '#', 'icon' => 'briefcase', 'sort_order' => 20],
            ['name' => 'Folios', 'url' => '#', 'icon' => 'folder', 'sort_order' => 30],
            ['name' => 'CRM', 'url' => '#', 'icon' => 'users', 'sort_order' => 40],
            ['name' => 'Cotizador', 'url' => '#', 'icon' => 'calc', 'sort_order' => 50],
            ['name' => 'Fichas técnicas', 'url' => '#', 'icon' => 'file', 'sort_order' => 60],
            ['name' => 'Garantías', 'url' => '#', 'icon' => 'shield', 'sort_order' => 70],
        ];

        foreach ($items as $i) {
            QuickLink::updateOrCreate(
                ['name' => $i['name']],
                array_merge($i, ['is_active' => true])
            );
        }
    }
}