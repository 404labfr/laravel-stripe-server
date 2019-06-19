<?php

namespace Lab404\StripeServer;

use App\Console\Commands\Events;
use App\Console\Commands\StripeCompletedCheckout;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class StripeServerServiceProvider extends BaseServiceProvider
{
    /** @var string $configName */
    protected $configName = 'stripe-server';

    public function register()
    {
        $this->mergeConfig();

        $this->app->singleton(Stripe::class, function () {
            return new Stripe(
                Config::get('services.stripe.key'),
                Config::get('services.stripe.secret')
            );
        });

        $this->app->alias(Stripe::class, 'stripe');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Events::class,
                StripeCompletedCheckout::class,
            ]);
        }
    }

    public function boot()
    {
        $this->publishConfig();
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
        $this->loadViewsFrom(__DIR__ . '/../views', $this->configName);
    }

    protected function mergeConfig()
    {
        $configPath = __DIR__ . '/../config/' . $this->configName . '.php';

        $this->mergeConfigFrom($configPath, $this->configName);
    }

    protected function publishConfig()
    {
        $configPath = __DIR__ . '/../config/' . $this->configName . '.php';

        $this->publishes([$configPath => config_path($this->configName . '.php')], 'impersonate');
    }
}