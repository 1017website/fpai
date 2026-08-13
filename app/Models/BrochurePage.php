<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrochurePage extends Model
{
    protected $fillable = [
        'page_number', 'position', 'slug', 'label', 'image_path', 'theme',
        'alt_text', 'show_in_navigation', 'navigation_label', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'show_in_navigation' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
}
