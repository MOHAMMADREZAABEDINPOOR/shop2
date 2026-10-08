<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'name_en',
        'name_fa',
        'slug',
        'description',
        'description_en',
        'description_fa',
        'image',
        'icon',
        'is_active',
        'sort_order',
        'seo_title',
        'seo_description',
    ];

    public function getNameAttribute(?string $value): string
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && ! empty($this->attributes['name_en'])) {
            return $this->attributes['name_en'];
        }
        if ($locale === 'fa' && ! empty($this->attributes['name_fa'])) {
            return $this->attributes['name_fa'];
        }

        return $value ?? '';
    }

    public function getDescriptionAttribute(?string $value): ?string
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && ! empty($this->attributes['description_en'])) {
            return $this->attributes['description_en'];
        }
        if ($locale === 'fa' && ! empty($this->attributes['description_fa'])) {
            return $this->attributes['description_fa'];
        }

        return $value;
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeRoot(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        return asset('storage/'.$this->image);
    }

    public function getAllChildrenIds(): array
    {
        $ids = [$this->id];
        foreach ($this->children as $child) {
            $ids = array_merge($ids, $child->getAllChildrenIds());
        }

        return $ids;
    }
}
