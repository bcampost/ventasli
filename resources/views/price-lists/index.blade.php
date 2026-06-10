<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PriceListController extends Controller
{
    public function index()
    {
        $dir = public_path('pdfs');

        $files = [];

        if (is_dir($dir)) {
            $items = scandir($dir) ?: [];

            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;

                $full = $dir . DIRECTORY_SEPARATOR . $item;
                if (!is_file($full)) continue;

                $lower = strtolower($item);
                if (!str_ends_with($lower, '.pdf')) continue;

                $files[] = [
                    'filename' => $item,
                    'url' => '/pdfs/' . $item,
                    'title' => $this->humanTitle($item),
                ];
            }
        }

        usort($files, fn ($a, $b) => strcmp($a['filename'], $b['filename']));

        return view('price-lists.index', [
            'files' => $files,
        ]);
    }

    private function humanTitle(string $filename): string
    {
        $name = preg_replace('/\.pdf$/i', '', $filename);
        $name = str_replace(['-', '_'], ' ', $name);
        $name = preg_replace('/\s+/', ' ', $name);
        return trim(ucwords($name));
    }
}