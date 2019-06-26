<?php

namespace Lab404\StripeServer\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Config;

/**
 * Trait HasStripeCheckout
 * @package Lab404\StripeServer\Models
 * @method MorphOne morphOne(string $related, string $name, string $type = null, string $id = null, string $localKey = null)
 */
trait HasStripeCheckout
{
    public function checkout(): MorphOne
    {
        return $this->morphOne(Config::get('stripe-server.model'), 'chargeable');
    }

    public function scopeHasCheckout(Builder $query): void
    {
        $query->has('checkout');
    }

    public function scopeHasPaidCheckout(Builder $query): void
    {
        $query->whereHas('checkout', function ($query) {
            $query->where('is_paid', '=', 1);
        });
    }

    public function scopeHasUnpaidCheckout(Builder $query): void
    {
        $query->whereHas('checkout', function ($query) {
            $query->where('is_paid', '=', 0);
        });
    }
}
