<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'name', 'slug', 'label', 'description',
        'icon', 'color_from', 'color_to', 'text_color',
        'type', 'condition_key', 'priority', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getGradientStyleAttribute(): string
    {
        return "background: linear-gradient(135deg, {$this->color_from}, {$this->color_to});";
    }
}
