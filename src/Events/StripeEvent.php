<?php

namespace Lab404\StripeServer\Events;

use Illuminate\Queue\SerializesModels;
use Stripe\ApiResource;

class StripeEvent
{
    use SerializesModels;

    /** @var ApiResource $event */
    public $event;

    public function __construct(ApiResource $event)
    {
        $this->event = $event;
    }
}