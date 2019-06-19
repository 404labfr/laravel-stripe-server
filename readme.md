# Laravel Stripe Server

Laravel Stripe Server is a library to handle Stripe SCA checkout.

- [Requirements](#requirements)
- [Intended workflow](#intended-workflow)
- [Installation](#installation)
- [Going deeper](#going-deeper)
- [Tests](#tests)
- [Contribute](#contribute)
- [Licence](#licence)
 
## Requirements

- Laravel >= 5.8
- PHP >= 7.1

## Intended workflow

1. You have a `Order` model with a stripe checkout. Your create the order in your controller.

```php
use App\Models\Order;
use Lab404\StripeServer\Facades\Stripe;
use Illuminate\Http\Request;

class OrderController
{
    public function store(Request $request)
    {
        // Create your order
        $order = new Order($request->validated());
        $order->save();
        
        // Create your checkout session
        $session = Stripe::requestCreateSession();
        $session->setCustomerEmail($request->user()->email);
        $session->setReturnUrls($confirm_url, $cancel_url);
        $session->setProduct('T-shirt', 5000, 'EUR', 1, $picture_url);
        
        $response = $session->call();
        
        // Create your checkout model
        $checkout = Stripe::registerCheckout($session, $order);
        
        return Stripe::redirectSession($session->id);
    }
}
```

2. Your user is redirected to stripe, he fills his informations and he his redirected to your success URL. The order is not paid yet.
Asynchronously, the plugin with try to get new events from Stripe and will mark the checkout as paid by dispatching the `CheckoutSessionCompleted` event:

```php
class CheckoutEventSubscriber
{
    public function subscribe($events)
    {
        $events->listen(
            'Lab404\StripeServer\Events\CheckoutSessionCompleted',
            [$this, 'handle']
        );
    }
    
    public function handle($event)
    {
        /** @var Lab404\StripeServer\Models\StripeCheckout $checkout */
        $checkout = $event->checkout;
        /** Your charged model */
        $chargeable = $checkout->chargeable;
      
        /** Important! Mark the checkout as paid */
        $checkout->is_paid = true;
        $checkout->save();
    }
}
```

## Installation

1. Require it with Composer:
```bash
composer require lab404/laravel-stripe-server
```

2. Configure your Stripe keys in `config/services.php`.

3. Publish `migrations` and `views` with `php artisan vendor:publish --tag=stripe-server`.

4. Migrate `2019_06_19_101000_create_stripe_checkouts_table.php`.

5. Schedule the command in `app\Console\Kernel.php`:
```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('stripe:checkout-session-completed')->everyMinute();
}
```

6. Add the `Lab404\StripeServer\Models\HasStripeCheckout` to your chargeable models. 

## Going deeper

### Stripe documentation

- [Checkout Server Quickstart](https://stripe.com/docs/payments/checkout/server)
- [Checkout Purchase Fulfillment](https://stripe.com/docs/payments/checkout/fulfillment)
- [Going Live with Checkout](https://stripe.com/docs/payments/checkout/live)
- [Strong Customer Authentication](https://stripe.com/docs/strong-customer-authentication)

### Access the Stripe Manager

With facade:
```php
Stripe::method();
```

With DI:
```
public function index(Lab404\StripeServer\Stripe $stripe)
{
    $stripe->method();
}
```

With container:
```
app('stripe')->method();
```

### Customize the `StripeCheckout` model

TODO

See `config/stripe-server.php`.

### Customize the redirector

TODO

See the `stripe-server::redirector` view.

### Work with others Stripe events

TODO

See `Lab404\StripeServer\Commands\Events` and `Lab404\StripeServer\Events\StripeEvent`.

## Tests

TODO

## Contribute

This package is still in development, feel free to contribute!

### Contributors

- [MarceauKa](https://github.com/MarceauKa)
- and all others [contributors](https://github.com/404labfr/laravel-impersonate/graphs/contributors)

## Licence

MIT
