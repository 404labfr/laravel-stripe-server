<?php

namespace Lab404\StripeServer\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Config;

/**
 * Trait HasStripeCheckouts
 * @package Lab404\StripeServer\Models
 * @method MorphMany morphMany($related, $name, $type = null, $id = null, $localKey = null)
 */
trait HasStripeCheckouts
{
    public function checkouts(): MorphMany
    {
        return $this->morphMany(Config::get('stripe-server.model'), 'chargeable');
    }

    public function scopeHasCheckouts(Builder $query): void
    {
        $query->has('checkouts');
    }

    public function scopeHasPaidCheckouts(Builder $query): void
    {
        $query->whereHas('checkouts', function ($query) {
            $query->where('is_paid', '=', 1);
        });
    }

    public function scopeHasUnpaidCheckouts(Builder $query): void
    {
        $query->whereHas('checkouts', function ($query) {
            $query->where('is_paid', '=', 0);
        });
    }
}
