<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'title_en',
        'title_fa',
        'subtitle',
        'subtitle_en',
        'subtitle_fa',
        'image_path',
        'mobile_image_path',
        'link_url',
        'badge_text',
        'badge_text_en',
        'badge_text_fa',
        'position',
        'sort_order',
        'is_active',
    ];

    public function getTitleAttribute(?string $value): string
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && ! empty($this->attributes['title_en'])) {
            return $this->attributes['title_en'];
        }
        if ($locale === 'fa' && ! empty($this->attributes['title_fa'])) {
            return $this->attributes['title_fa'];
        }

        return $value ?? '';
    }

    public function getSubtitleAttribute(?string $value): ?string
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && ! empty($this->attributes['subtitle_en'])) {
            return $this->attributes['subtitle_en'];
        }
        if ($locale === 'fa' && ! empty($this->attributes['subtitle_fa'])) {
            return $this->attributes['subtitle_fa'];
        }

        return $value;
    }

    public function getBadgeTextAttribute(?string $value): ?string
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && ! empty($this->attributes['badge_text_en'])) {
            return $this->attributes['badge_text_en'];
        }
        if ($locale === 'fa' && ! empty($this->attributes['badge_text_fa'])) {
            return $this->attributes['badge_text_fa'];
        }

        return $value;
    }

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function scopePosition(Builder $query, string $position): Builder
    {
        return $query->where('position', $position);
    }

    public function getImageUrlAttribute(): string
    {
        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        return asset('storage/'.$this->image_path);
    }
}
