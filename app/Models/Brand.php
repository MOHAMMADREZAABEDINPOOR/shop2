<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_en',
        'name_fa',
        'slug',
        'logo',
        'description',
        'description_en',
        'description_fa',
        'website',
        'is_active',
        'seo_title',
        'seo_description',
    ];

    public function getNameAttribute(?string $value): string
    {
        $locale = app()->getLocale();
        if ($locale === 'en') {
            if (! empty($this->attributes['name_en'])) {
                return $this->attributes['name_en'];
            }
            if (! empty($value) && preg_match('/\(([^)]+)\)/u', $value, $matches)) {
                return trim($matches[1]);
            }

            return $value ?? '';
        }

        if ($locale === 'fa') {
            if (! empty($this->attributes['name_fa'])) {
                return $this->attributes['name_fa'];
            }
            if (! empty($value)) {
                $stripped = trim(preg_replace('/\s*\([^)]+\)\s*/u', '', $value));
                if ($stripped !== '') {
                    return $stripped;
                }
            }

            return $value ?? '';
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
        ];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
