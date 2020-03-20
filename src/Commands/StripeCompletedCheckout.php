<?php

namespace Lab404\StripeServer\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Lab404\StripeServer\Events\CheckoutSessionCompleted;
use Lab404\StripeServer\Models\StripeCheckout;
use Lab404\StripeServer\Stripe;

class StripeCompletedCheckout extends Command
{
    /** @var string $signature */
    protected $signature = 'stripe:checkout-session-completed {--period=24 : Period in hours}';
    /** @var string $description */
    protected $description = 'Get all checkout session completed events';

    public function handle(Stripe $stripe)
    {
        $events = $stripe->requestSessionCheckoutCompletedEvents($this->option('period'))->call();

        if ($events->isEmpty()) {
            $this->info('No events found for this period.');
        }

        $ids = $events->pluck('payment_intent')->toArray();
        $model = Config::get('stripe-server.model');
        $model = $model ?? StripeCheckout::class;

        /** @var Collection $checkouts */
        $checkouts = $model::with('chargeable')->paymentIntentIdsAre($ids)->isNotPaid()->get();
        $checkouts->each(function ($checkout) use ($stripe) {
            try {
                $this->info('Dispatching ' . $checkout->payment_intent_id);
                $payment = $stripe->requestPaymentIntent($checkout->payment_intent_id)->call();
                if ($payment && $payment->status === 'succeeded') {
                    Event::dispatch(new CheckoutSessionCompleted($checkout, $payment));
                }
            } catch (\Exception $e) {
                unset($e);
            }
        });
    }
}
