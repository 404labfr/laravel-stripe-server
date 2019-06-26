<?php

namespace Lab404\StripeServer\Events;

use Illuminate\Queue\SerializesModels;
use Stripe\PaymentIntent as StripePaymentIntent;
use Lab404\StripeServer\Models\StripeCheckout;

class CheckoutSessionCompleted
{
    use SerializesModels;

    /** @var StripeCheckout $checkout */
    public $checkout;
    /** @var StripePaymentIntent $paymentIntent */
    public $paymentIntent;

    public function __construct(StripeCheckout $checkout, StripePaymentIntent $paymentIntent)
    {
        $this->checkout = $checkout;
        $this->paymentIntent = $paymentIntent;
    }
}