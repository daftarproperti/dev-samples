<?php

namespace App\Models;

use App\Helpers\CurrencyHelper;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Listing extends Model
{
    protected $collection = 'listings';

    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? nl2br(e($value)) : '',
        );
    }

    public function getFormattedPriceAttribute(): string
    {
        return CurrencyHelper::formatCurrency($this->price);
    }

    public function getFormattedRentPriceAttribute(): ?string
    {
        return $this->rentPrice ? CurrencyHelper::formatCurrency($this->rentPrice) : null;
    }

    public function getThumbnailAttribute(): string
    {
        return $this->pictureUrls[0] ?? 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI2ODAuNzY0IiBoZWlnaHQ9IjUyOC4zNTQiIHZpZXdCb3g9IjAgMCAxODAuMTE5IDEzOS43OTQiPjxnIHRyYW5zZm9ybT0idHJhbnNsYXRlKC0xMy41OSAtNjYuNjM5KSIgcGFpbnQtb3JkZXI9ImZpbGwgbWFya2VycyBzdHJva2UiPjxwYXRoIGZpbGw9IiNkMGQwZDAiIGQ9Ik0xMy41OTEgNjYuNjM5SDE5My43MXYxMzkuNzk0SDEzLjU5MXoiLz48cGF0aCBkPSJtMTE4LjUwNyAxMzMuNTE0LTM0LjI0OSAzNC4yNDktMTUuOTY4LTE1Ljk2OC00MS45MzggNDEuOTM3SDE3OC43MjZ6IiBvcGFjaXR5PSIuNjc1IiBmaWxsPSIjZmZmIi8+PGNpcmNsZSBjeD0iNTguMjE3IiBjeT0iMTA4LjU1NSIgcj0iMTEuNzczIiBvcGFjaXR5PSIuNjc1IiBmaWxsPSIjZmZmIi8+PHBhdGggZmlsbD0ibm9uZSIgZD0iTTI2LjExMSA3Ny42MzRoMTUyLjYxNHYxMTYuMDk5SDI2LjExMXoiLz48L2c+PC9zdmc+';
    }

    public function getTypeNameAttribute(): string
    {
        switch ($this->propertyType) {
            case 'land':
                return 'Tanah';
            case 'house':
                return 'Rumah';
            case 'apartment':
                return 'Apartemen';
            default:
                return '';
        }
    }

    public function getFacingNameAttribute(): string
    {
        switch ($this->facing) {
            case 'north':
                return 'Utara';
            case 'south':
                return 'Selatan';
            case 'east':
                return 'Timur';
            case 'west':
                return 'Barat';
            default:
                return '';
        }
    }

    public function getRevealUrlAttribute(): string
    {
        $revealUrl = sprintf(
            '%s/witness?listingId=%s&referrerId=%s',
            config('services.daftarproperti.reveal_base_url'),
            $this->listingIdStr,
            urlencode(config('services.daftarproperti.reveal_referrer_id'))
        );

        return $revealUrl;
    }

}
