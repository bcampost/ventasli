<?php

namespace App\Services;

use App\Models\IngenieriaPdf;

class IngenieriaService
{
    public function getDocs($code)
    {
        $code = strtoupper(trim((string)$code));

        if (!$code) {
            return [
                'ficha' => null,
                'instructivo' => null,
                'impresion' => null,
            ];
        }

        $files = IngenieriaPdf::where('is_active', 1)
            ->where(function($q) use ($code){
                $q->where('name', $code.'F')
                  ->orWhere('name', $code.'I')
                  ->orWhere('name', $code.'IMP');
            })
            ->get()
            ->keyBy('name');

        return [
            'ficha' => $files[$code.'F'] ?? null,
            'instructivo' => $files[$code.'I'] ?? null,
            'impresion' => $files[$code.'IMP'] ?? null,
        ];
    }

    public function url($file)
    {
        if (!$file) return null;

        return env('INGENIERIA_URL') . '/files/' . $file->id;
    }
}