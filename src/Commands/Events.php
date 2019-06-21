<?php

namespace Lab404\StripeServer\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Event;
use Lab404\StripeServer\Stripe;
use Lab404\StripeServer\Events\StripeEvent;

class Events extends Command
{
    /** @var string $signature */
    protected $signature = 'stripe:events {--period=24 : Period in hours}';
    /** @var string $description */
    protected $description = 'Get all stripe events within period';

    public function handle(Stripe $stripe)
    {
        $events = $stripe->eventsCommand($this->option('period'))->call();

        if ($events->isEmpty()) {
            $this->info('No events found for this period.');
        }

        $events->each(function ($event) {
            $this->info('Dispatching ' . $event->id);
            Event::dispatch(new StripeEvent($event));
        });
    }
}
