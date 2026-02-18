<?php
// app/Support/ImageStandardizer.php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageStandardizer
{
    /**
     * Recorta a un estándar fijo (ej. 1600x900) y guarda en storage public.
     *
     * @return string ruta relativa (ej. "menu/cards/abc.webp")
     */
    public static function storeCover(UploadedFile $file, string $diskDir, int $w = 1600, int $h = 900): string
    {
        $manager = new ImageManager(new Driver());

        $img = $manager->read($file->getPathname());

        // cover = recorta para llenar exacto WxH (centrado)
        $img = $img->cover($w, $h);

        // Guardamos como WEBP (pro, ligero)
        $name = uniqid('img_', true) . '.webp';
        $path = trim($diskDir, '/') . '/' . $name;

        $binary = (string) $img->toWebp(85);

        Storage::disk('public')->put($path, $binary);

        return $path;
    }

    public static function deleteIfExists(?string $path): void
    {
        if (!$path) return;
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}