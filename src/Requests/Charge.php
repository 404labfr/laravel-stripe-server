<?php

namespace Lab404\StripeServer\Requests;

use Stripe\Charge as StripeCharge;

class Charge extends Command
{
    protected $params = [
        'amount' => null,
        'currency' => null,
        'source' => null,
        'description' => null,
    ];

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function call(): StripeCharge
    {
        return parent::call();
    }

    protected function class(): string
    {
        return \Stripe\Charge::class;
    }

    protected function method(): string
    {
        return 'create';
    }
}
