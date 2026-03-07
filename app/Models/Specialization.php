<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Specialization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'parent_id',
        'is_parent',
    ];

    protected $casts = [
        'is_parent' => 'boolean',
    ];

    public function lawyerProfiles(): BelongsToMany
    {
        return $this->belongsToMany(LawyerProfile::class, 'lawyer_specializations');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Specialization::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Specialization::class, 'parent_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Scope for parent specializations only
    public function scopeParents($query)
    {
        return $query->where('is_parent', true)->orWhereNull('parent_id');
    }

    // Scope for sub-specializations only
    public function scopeChildren($query)
    {
        return $query->whereNotNull('parent_id');
    }
}
