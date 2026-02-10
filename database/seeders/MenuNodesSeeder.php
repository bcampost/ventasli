<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuNode;

class MenuNodesSeeder extends Seeder
{
    private function slugify(string $text): string
    {
        $text = mb_strtolower(trim($text));
        $text = preg_replace('/[^\p{L}\p{N}]+/u', '-', $text);
        $text = trim($text, '-');
        return $text ?: 'menu';
    }

    private function makeKey(?string $parentKey, string $slug): string
    {
        $slug = $this->slugify($slug);
        return $parentKey ? ($parentKey . '/' . $slug) : $slug;
    }

    public function run(): void
    {
        $menu = config('menu', []);
        if (empty($menu)) return;

        // si ya existe algo, no duplica (puedes cambiar a truncate si prefieres)
        if (MenuNode::count() > 0) return;

        $walk = function (array $items, ?MenuNode $parent = null) use (&$walk) {
            $i = 0;
            foreach ($items as $item) {
                $label = $item['label'] ?? 'Sin título';
                $slug  = $this->slugify($label);
                $key   = $this->makeKey($parent?->key, $slug);

                $node = MenuNode::create([
                    'parent_id'   => $parent?->id,
                    'label'       => $label,
                    'slug'        => $slug,
                    'key'         => $key,
                    'url'         => $item['url'] ?? null,
                    'title'       => null,
                    'description' => null,
                    'image_path'  => null,
                    'sort'        => $i,
                    'is_active'   => true,
                ]);

                $children = $item['children'] ?? [];
                if (is_array($children) && count($children)) {
                    $walk($children, $node);
                }

                $i++;
            }
        };

        $walk($menu, null);
    }
}