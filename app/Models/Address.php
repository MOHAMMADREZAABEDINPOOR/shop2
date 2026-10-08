<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'recipient_name',
        'recipient_phone',
        'province',
        'city',
        'postal_code',
        'address_line',
        'unit',
        'plaque',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFullAddressAttribute(): string
    {
        $parts = [$this->province, $this->city, $this->address_line];
        if ($this->plaque) {
            $parts[] = __('پلاک').' '.$this->plaque;
        }
        if ($this->unit) {
            $parts[] = __('واحد').' '.$this->unit;
        }

        $separator = app()->getLocale() === 'fa' ? '، ' : ', ';

        return implode($separator, $parts);
    }
}
