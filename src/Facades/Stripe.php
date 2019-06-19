<?php

namespace Lab404\StripeServer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class StripeServer
 * @package Lab404\StripeServer
 * @method \Lab404\StripeServer\Requests\Charge charge
 * @method \Lab404\StripeServer\Requests\CreateSession createSession
 * @method \Lab404\StripeServer\Requests\Events events(string $type, int $hours = 24)
 * @method \Lab404\StripeServer\Requests\Events sessionCheckoutCompletedEvents(int $hours = 24)
 */
class Stripe extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'stripe';
    }
}