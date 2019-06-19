<?php

namespace Lab404\StripeServer;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Lab404\StripeServer\Requests\Charge;
use Lab404\StripeServer\Requests\CreateSession;
use Lab404\StripeServer\Requests\Events;
use Stripe\Stripe as StripeCore;

class Stripe
{
    /** @var string $key */
    protected $key;
    /** @var string $secret */
    protected $secret;

    public function __construct(string $key, string $secret)
    {
        $this->key = $key;
        $this->secret = $secret;

        StripeCore::setApiKey($this->secret);
    }

    public function redirectSession(string $session_id): View
    {
        return view('stripe-server::redirector')->with([
            'session_id' => $session_id,
        ]);
    }

    public function registerCheckout(\Stripe\Charge $session, Model $model): Model
    {
        $class = Config::get('stripe-server.model');

        $checkout = new $class([
            'payment_intent_id' => $session->payment_intent,
            'checkout_session_id' => $session->id,
        ]);

        if ($model->exists) {
            $checkout->chargeable_type = get_class($model);
            $checkout->chargeable_id = $model->getKey();
        }

        $checkout->save();

        return $checkout;
    }

    public function requestCharge(): Charge
    {
        return new Charge();
    }

    public function requestCreateSession(): CreateSession
    {
        return new CreateSession();
    }

    public function requestEvents(string $type, int $hours = 24): Events
    {
        return new Events($type, $hours);
    }

    public function requestSessionCheckoutCompletedEvents(int $hours = 24): Events
    {
        return $this->events('checkout.session.completed', $hours);
    }
}
