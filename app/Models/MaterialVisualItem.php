<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialVisualItem extends Model
{
    protected $fillable = [
        'section',
        'parent_key',
        'title',
        'description',
        'type',
        'file_path',
        'thumb_path',
        'external_url',
        'sort',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function fileUrl(): ?string
    {
        if ($this->external_url) {
            return $this->external_url;
        }

        if ($this->file_path) {
            return asset('storage/' . ltrim($this->file_path, '/'));
        }

        return null;
    }

    public function thumbUrl(): ?string
    {
        if ($this->thumb_path) {
            return asset('storage/' . ltrim($this->thumb_path, '/'));
        }

        return $this->fileUrl();
    }
}