<?php

namespace Lab404\StripeServer\Models;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Config;

/**
 * Trait HasStripeCheckout
 * @package Lab404\StripeServer\Models
 * @method MorphMany morphMany($related, $name, $type = null, $id = null, $localKey = null)
 */
trait HasStripeCheckout
{
    public function checkouts(): MorphMany
    {
        return $this->morphMany(Config::get('stripe-server.model'), 'chargeable');
    }
}
