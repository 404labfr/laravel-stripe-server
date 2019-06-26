<?php

namespace Lab404\StripeServer\Requests;

use Stripe\PaymentIntent as StripePaymentIntent;

class PaymentIntent extends Command
{
    public function setId(string $id): self
    {
        $this->params = $id;
        return $this;
    }

    protected function class(): string
    {
        return StripePaymentIntent::class;
    }

    protected function method(): string
    {
        return 'retrieve';
    }
}
