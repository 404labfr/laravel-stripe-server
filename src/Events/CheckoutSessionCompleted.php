<?php

namespace Lab404\StripeServer\Events;

use Illuminate\Queue\SerializesModels;
use Lab404\StripeServer\Models\StripeCheckout;

class CheckoutSessionCompleted
{
    use SerializesModels;

    public $checkout;

    public function __construct(StripeCheckout $checkout)
    {
        $this->checkout = $checkout;
    }
}