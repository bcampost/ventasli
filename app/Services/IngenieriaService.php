<?php

namespace App\Services;

use App\Models\IngenieriaPdf;
use Illuminate\Support\Str;

class IngenieriaService
{
    public function getDocs($code)
    {
        $raw = strtoupper(trim((string) $code));

        // Si viene F106, 106F, 106I, 106IMP, deja solo 106
        $code = preg_replace('/^(F|I|IMP)/', '', $raw);
        $code = preg_replace('/(F|I|IMP|\.PDF)$/', '', $code);
        $code = trim($code);
        $code = strtoupper(trim((string) $code));

        if (!$code) {
            return [
                'ficha' => null,
                'instructivo' => null,
                'impresion' => null,
            ];
        }

        // 🔥 Busca TODO lo que contenga el número
        $files = IngenieriaPdf::where('is_active', 1)
            ->where(function ($q) use ($code) {
                $q->where('name', 'like', "%{$code}%")
                    ->orWhere('original_name', 'like', "%{$code}%");
            })
            ->get();

        // 🔥 Clasifica por tipo
        $ficha = null;
        $inst = null;
        $imp = null;

        foreach ($files as $file) {
            $name = strtoupper($file->name . ' ' . $file->original_name);

            if (!$ficha && str_contains($name, $code) && str_contains($name, 'F')) {
                $ficha = $file;
            }

            if (!$inst && str_contains($name, $code) && str_contains($name, 'I')) {
                $inst = $file;
            }

            if (!$imp && str_contains($name, 'IMP')) {
                $imp = $file;
            }
        }

        return [
            'ficha' => $ficha,
            'instructivo' => $inst,
            'impresion' => $imp,
        ];
    }

    public function getDocsForProduct($product)
    {
        $code = strtoupper(trim((string) ($product->ingenieria_code ?? '')));

        if (!$code) {
            $code = $this->detectCodeFromProduct($product);
        }

        return $this->getDocs($code);
    }

    public function detectCodeFromProduct($product): ?string
    {
        $text = strtoupper(trim(
            ($product->title ?? '') . ' ' .
            ($product->menu_key ?? '') . ' ' .
            ($product->description ?? '')
        ));

        if ($text === '') {
            return null;
        }

        $codes = IngenieriaPdf::where('is_active', 1)
            ->where('category', 'Fichas Técnicas')
            ->pluck('name')
            ->map(function ($name) {
                $name = strtoupper(trim((string) $name));
                return preg_replace('/(IMP|F|I)$/', '', $name);
            })
            ->filter()
            ->unique()
            ->sortByDesc(fn($code) => strlen($code))
            ->values();

        foreach ($codes as $code) {
            if ($code !== '' && Str::contains($text, $code)) {
                return $code;
            }
        }

        return null;
    }

public function url($file)
{
    if (!$file) {
        return null;
    }

    $base = rtrim(env('INGENIERIA_URL'), '/');

    if (is_string($file)) {
        return $base . '/files/' . base64_encode($file);
    }

    if (!empty($file->file_path)) {
        return $base . '/files/' . base64_encode($file->file_path);
    }

    return null;
}

public function downloadUrl($file)
{
    if (!$file) {
        return null;
    }

    $base = rtrim(env('INGENIERIA_URL'), '/');

    if (is_string($file)) {
        return $base . '/files/' . base64_encode($file) . '/download';
    }

    if (!empty($file->file_path)) {
        return $base . '/files/' . base64_encode($file->file_path) . '/download';
    }

    return null;
}
public function guessDocsByCode($code): array
{
    $raw = strtoupper(trim((string) $code));

    $code = preg_replace('/\.PDF$/i', '', $raw);
    $code = preg_replace('/^(F|I|IMP)/i', '', $code);
    $code = preg_replace('/(F|I|IMP)$/i', '', $code);
    $code = trim($code);

    return [
        'ficha' => "fichas/{$code}F.pdf",
        'instructivo' => "instructivos/{$code}I.pdf",
        'impresion' => "instructivos/{$code}IMP.pdf",
    ];
}

}