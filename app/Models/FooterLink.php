<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class FooterLink extends Model
{
    protected $table = 'footer_links';

    protected $fillable = [
        'group','key','label',
        'link_mode',
        'menu_path','external_url',
        'file_path','file_disk','file_original','file_mime','file_size',
        'sort','is_active'
    ];

    protected $casts = [
        'sort' => 'integer',
        'is_active' => 'boolean',
    ];


    public function fileUrl(): ?string
    {
        if (!$this->file_path) return null;
        return Storage::disk($this->file_disk ?: 'public')->url($this->file_path);
    }
}