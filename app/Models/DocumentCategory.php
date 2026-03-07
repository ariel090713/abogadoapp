<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentCategory extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'icon_color',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope to get only active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get categories ordered by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get color classes for UI
     */
    public function getColorClasses()
    {
        $colors = [
            'blue' => [
                'bg' => 'bg-primary-100',
                'text' => 'text-primary-700',
                'hover_bg' => 'group-hover:bg-primary-600',
                'hover_text' => 'group-hover:text-white',
            ],
            'red' => [
                'bg' => 'bg-accent-100',
                'text' => 'text-accent-700',
                'hover_bg' => 'group-hover:bg-accent-600',
                'hover_text' => 'group-hover:text-white',
            ],
            'green' => [
                'bg' => 'bg-green-100',
                'text' => 'text-green-700',
                'hover_bg' => 'group-hover:bg-green-600',
                'hover_text' => 'group-hover:text-white',
            ],
            'purple' => [
                'bg' => 'bg-purple-100',
                'text' => 'text-purple-700',
                'hover_bg' => 'group-hover:bg-purple-600',
                'hover_text' => 'group-hover:text-white',
            ],
            'indigo' => [
                'bg' => 'bg-blue-100',
                'text' => 'text-blue-700',
                'hover_bg' => 'group-hover:bg-blue-600',
                'hover_text' => 'group-hover:text-white',
            ],
            'gray' => [
                'bg' => 'bg-gray-100',
                'text' => 'text-gray-700',
                'hover_bg' => 'group-hover:bg-gray-600',
                'hover_text' => 'group-hover:text-white',
            ],
        ];

        return $colors[$this->icon_color] ?? $colors['gray'];
    }
}
