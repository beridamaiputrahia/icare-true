<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use BelongsToTenant;
    protected $fillable = [
        'name', 'slug', 'label', 'description',
        'icon', 'color_from', 'color_to', 'text_color',
        'type', 'condition_key', 'priority', 'is_active',
        'tenant_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getGradientStyleAttribute(): string
    {
        return "background: linear-gradient(135deg, {$this->color_from}, {$this->color_to});";
    }
}
