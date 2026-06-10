<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Slide extends Model
{
    protected $fillable = [
        'title',
        'image_path',
        'link',
        'is_active',
        'expires_at',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'expires_at' => 'datetime',
    ];

    /**
     * Devuelve uno de: 'nuevo', 'vigente', 'expira_pronto', 'vencido'.
     *
     * Reglas:
     *   - nuevo:         los primeros 3 días desde created_at
     *   - vigente:       3 días después de cargado hasta 2 días antes de expires_at
     *   - expira_pronto: 2 días antes de expires_at hasta expires_at
     *   - vencido:       después de expires_at
     *
     * Si no hay expires_at, sólo cambia entre 'nuevo' y 'vigente'.
     */
    public function status(): string
    {
        $now = Carbon::now();
        $created = $this->created_at ? Carbon::parse($this->created_at) : $now;
        $expires = $this->expires_at ? Carbon::parse($this->expires_at) : null;

        if ($expires && $now->greaterThan($expires)) {
            return 'vencido';
        }

        if ($expires && $now->greaterThanOrEqualTo($expires->copy()->subDays(2))) {
            return 'expira_pronto';
        }

        if ($created->copy()->addDays(3)->greaterThan($now)) {
            return 'nuevo';
        }

        return 'vigente';
    }

    public function isNew(): bool
    {
        return $this->status() === 'nuevo';
    }
}