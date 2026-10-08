<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'name_en',
        'name_fa',
        'slug',
        'sku',
        'barcode',
        'description',
        'description_en',
        'description_fa',
        'short_description',
        'short_description_en',
        'short_description_fa',
        'price',
        'sale_price',
        'cost_price',
        'stock',
        'status',
        'is_featured',
        'is_best_seller',
        'is_new_arrival',
        'weight',
        'dimensions',
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

    public function getShortDescriptionAttribute(?string $value): ?string
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && ! empty($this->attributes['short_description_en'])) {
            return $this->attributes['short_description_en'];
        }
        if ($locale === 'fa' && ! empty($this->attributes['short_description_fa'])) {
            return $this->attributes['short_description_fa'];
        }

        return $value;
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
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'stock' => 'integer',
            'is_featured' => 'boolean',
            'is_best_seller' => 'boolean',
            'is_new_arrival' => 'boolean',
            'weight' => 'decimal:2',
            'dimensions' => 'array',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function inventory(): HasOne
    {
        return $this->hasOne(Inventory::class)->whereNull('product_variant_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeBestSeller(Builder $query): Builder
    {
        return $query->where('is_best_seller', true);
    }

    public function scopeNewArrival(Builder $query): Builder
    {
        return $query->where('is_new_arrival', true);
    }

    public function getEffectivePriceAttribute(): float
    {
        if ($this->sale_price !== null && $this->sale_price > 0 && $this->sale_price < $this->price) {
            return (float) $this->sale_price;
        }

        return (float) $this->price;
    }

    public function getHasDiscountAttribute(): bool
    {
        return $this->sale_price !== null && $this->sale_price > 0 && $this->sale_price < $this->price;
    }

    public function getDiscountPercentAttribute(): int
    {
        if (! $this->has_discount || $this->price <= 0) {
            return 0;
        }

        return (int) round((($this->price - $this->sale_price) / $this->price) * 100);
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        $primary = $this->primaryImage ?? $this->images->first();
        if ($primary && $primary->image_path) {
            if (str_starts_with($primary->image_path, 'http://') || str_starts_with($primary->image_path, 'https://')) {
                return $primary->image_path;
            }

            return asset('storage/'.$primary->image_path);
        }

        return asset('images/placeholder.svg');
    }

    public function getAverageRatingAttribute(): float
    {
        return (float) round($this->approvedReviews()->avg('rating') ?? 0, 1);
    }

    public function getReviewsCountAttribute(): int
    {
        return $this->approvedReviews()->count();
    }

    public function getIsInStockAttribute(): bool
    {
        return $this->stock > 0;
    }
}
