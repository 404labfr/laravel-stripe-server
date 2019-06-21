<?php

namespace Lab404\StripeServer\Requests;

class CreateSession extends Command
{
    protected $params = [
        'customer_email' => null,
        'payment_method_types' => ['card'],
        'line_items' => [],
        'locale' => 'auto',
        'submit_type' => 'pay',
        'success_url' => '',
        'cancel_url' => '',
    ];

    public function setCustomerEmail(string $email): self
    {
        $this->params['customer_email'] = $email;
        return $this;
    }

    public function setCustomer(string $identifier): self
    {
        $this->params['customer'] = $identifier;
        return $this;
    }

    /**
     * @param string|array $methods
     * @return self
     */
    public function setPaymentMethods($methods = 'card'): self
    {
        $this->params['payment_method_types'] = is_array($methods) ? $methods : [$methods];
        return $this;
    }

    public function setLocale(string $locale = 'auto'): self
    {
        $this->params['locale'] = $locale;
        return $this;
    }

    public function setSubmit(string $submit): self
    {
        $this->params['submit_type'] = $submit;
        return $this;
    }

    public function setSuccessUrl(string $succes_url): self
    {
        $this->params['success_url'] = $succes_url;
        return $this;
    }

    public function setCancelUrl(string $cancel_url): self
    {
        $this->params['cancel_url'] = $cancel_url;
        return $this;
    }

    public function setReturnUrls(string $success_url, string $cancel_url)
    {
        return $this->setSuccessUrl($success_url)->setCancelUrl($cancel_url);
    }

    /**
     * @param string $name
     * @param int    $amount
     * @param string $currency
     * @param int    $quantity
     * @param string|string[] $images
     * @return CreateSession
     */
    public function setProduct(string $name, int $amount, string $currency, int $quantity, $images): self
    {
        $this->params['line_items'] = [];
        return $this->addProduct($name, $amount, $currency, $quantity, $images);
    }

    /**
     * @param string $name
     * @param int    $amount
     * @param string $currency
     * @param int    $quantity
     * @param string|string[] $images
     * @return CreateSession
     */
    public function addProduct(string $name, int $amount, string $currency, int $quantity, $images): self
    {
        $this->params['line_items'][] = [
            'name' => $name,
            'quantity' => $quantity,
            'amount' => $amount,
            'currency' => $currency,
            'images' => is_array($images) ? $images : [$images],
        ];
        return $this;
    }

    protected function class(): string
    {
        return \Stripe\Checkout\Session::class;
    }

    protected function method(): string
    {
        return 'create';
    }
}
