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
     * Guarda una imagen "cover" (recorta para llenar exacto WxH) y la normaliza
     * Ideal si quieres tiles uniformes sin franjas, PERO recorta.
     *
     * @return string ruta relativa (ej. "menu/cards/abc.webp" o "menu/cards/abc.jpg")
     */
    public static function storeCover(UploadedFile $file, string $diskDir, int $w = 1600, int $h = 900): string
    {
        $manager = new ImageManager(new Driver());
        $img = $manager->read($file->getPathname());

        // cover = recorta para llenar exacto WxH (centrado)
        $img = $img->cover($w, $h);

        return self::encodeAndStore($img, $diskDir, 85);
    }

    /**
     * Guarda una imagen SIN RECORTAR (imagen completa).
     * Mantiene proporción y la acomoda dentro de WxH (puede haber "bandas" si no coincide ratio).
     *
     * @return string ruta relativa (ej. "menu/cards/abc.webp" o "menu/cards/abc.jpg")
     */
    public static function storeContain(UploadedFile $file, string $diskDir, int $w = 1600, int $h = 900): string
    {
        $manager = new ImageManager(new Driver());
        $img = $manager->read($file->getPathname());

        // contain = NO recorta. Encaja dentro de WxH conservando proporción.
        // Background neutro (puedes cambiarlo a transparente si guardas PNG).
        $img = $img->contain($w, $h, 'ffffff');

        return self::encodeAndStore($img, $diskDir, 85);
    }

    /**
     * Por si quieres decidir por parámetro sin cambiar controladores.
     * $mode: 'cover' (recorta) | 'contain' (no recorta)
     */
    public static function storeAuto(
        UploadedFile $file,
        string $diskDir,
        string $mode = 'contain',
        int $w = 1600,
        int $h = 900
    ): string {
        return $mode === 'cover'
            ? self::storeCover($file, $diskDir, $w, $h)
            : self::storeContain($file, $diskDir, $w, $h);
    }

    /**
     * Encoda a WEBP si está disponible; si no, cae a JPG.
     * Devuelve la ruta final que se guardó.
     */
    private static function encodeAndStore($img, string $diskDir, int $quality = 85): string
    {
        $diskDir = trim($diskDir, '/');
        $disk = Storage::disk('public');

        // Asegura carpeta
        if (!$disk->exists($diskDir)) {
            $disk->makeDirectory($diskDir);
        }

        $canWebp = function_exists('imagewebp');

        if ($canWebp) {
            $name = uniqid('img_', true) . '.webp';
            $path = $diskDir . '/' . $name;

            $binary = (string) $img->toWebp($quality);
            $saved = $disk->put($path, $binary);

            if (!$saved || !$disk->exists($path)) {
                throw new \RuntimeException("No se pudo guardar la imagen WEBP en: {$path}");
            }

            return $path;
        }

        // Fallback robusto: JPG
        $name = uniqid('img_', true) . '.jpg';
        $path = $diskDir . '/' . $name;

        $binary = (string) $img->toJpeg($quality);
        $saved = $disk->put($path, $binary);

        if (!$saved || !$disk->exists($path)) {
            throw new \RuntimeException("No se pudo guardar la imagen JPG en: {$path}");
        }

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