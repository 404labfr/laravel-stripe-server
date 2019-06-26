# Laravel Stripe Server

Laravel Stripe Server is a library to handle Stripe SCA checkout for your models.

- [Requirements](#requirements)
- [Intended workflow](#intended-workflow)
- [Installation](#installation)
- [Going deeper](#going-deeper)
- [Nova](#nova)
- [Tests](#tests)
- [Contribute](#contribute)
- [Licence](#licence)
 
## Requirements

- Laravel >= 5.8
- PHP >= 7.1

## Intended workflow

- You have an `Order` model with a stripe checkout. You create the order in your controller.

Example model:
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
        $session = Stripe::requestCreateSession()
                    ->setCustomerEmail($request->user()->email)
                    ->setReturnUrls($confirm_url, $cancel_url)
                    ->setProduct('T-shirt', 5000, 'EUR', 1, $picture_url)
                    ->call();
        
        // Create your checkout model
        $checkout = Stripe::registerCheckout($session, $order);
        
        return Stripe::redirectSession($response->id);
    }
}
```

- Your user is redirected to stripe, he fills his informations and he's redirected to your success URL. The order is not paid yet.
Asynchronously, the plugin will try to get new events from Stripe and will dispatch the `CheckoutSessionCompleted` event:

Example listener:
```php
use Lab404\StripeServer\Events\CheckoutSessionCompleted;
use Lab404\StripeServer\Models\StripeCheckout;

class CheckoutListener
{
    public function handle(CheckoutSessionCompleted $event): void
    {
        /** @var StripeCheckout $checkout */
        $checkout = $event->checkout;
        /** Your charged model */
        $chargeable = $checkout->chargeable;
        /** The PaymentIntent returned by Stripe */
        $payment = $event->paymentIntent;
      
        /** Important! Mark the checkout as paid */
        $checkout->markAsPaid();
    }
}
```

- You can use your model like this:
```php
$order = Order::with('checkout')->first();
if ($order->checkout->is_paid) {
    echo 'Order is paid';
} else {
    echo 'Order is not paid';
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

6. Add the `Lab404\StripeServer\Models\HasStripeCheckout` or `HasStripeCheckouts` (if a model can have multiple checkouts) to your chargeable models. 

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

### Available methods

- `redirectSession(string $session_id): Illuminate\Contracts\View\View`
- `registerCheckout(\Stripe\Checkout\Session $session, Model $model): Illuminate\Database\Eloquent\Model`
- `requestCreateCharge(): StripeServer\Requests\CreateCharge`
- `requestCreateSession(): StripeServer\Requests\CreateSession`
- `requestPaymentIntent(string $id): StripeServer\Requests\PaymentIntent`
- `requestEvents(string $type, int $hours = 24): StripeServer\Requests\Events`
- `requestSessionCheckoutCompletedEvents(int $hours = 24): StripeServer\Requests\Events`

### Working with your models

#### Model with many checkouts

When a model has the `Lab404\StripeServer\Models\HasStripeCheckouts` you have access to the following methods and scopes:
```php
// Scopes
Model::hasCheckouts()->get();
Model::hasPaidCheckouts()->get();
Model::hasUnpaidCheckouts()->get();

// Methods
$models->checkouts(); // returns all checkout for the model

// Eager loading
$models = Model::with('checkouts')->get(); 
```

#### Model with one checkout

When a model has the `Lab404\StripeServer\Models\HasStripeCheckout` you have access to the following methods and scopes:
```php
// Scopes
Model::hasCheckout()->get();
Model::hasPaidCheckout()->get();
Model::hasUnpaidCheckout()->get();

// Methods
$models->checkout(); // returns the checkout for the model

// Eager loading
$models = Model::with('checkout')->get(); 
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

## Nova

If you're using [Laravel Nova](https://nova.laravel.com) you can add the `Lab404\StripeServer\Nova\StripeCheckout` resource:

```
Laravel\Nova\Nova::resources([
    Lab404\StripeServer\Nova\StripeCheckout::class,
]);
```

This resource is not dynamically registered because it's quite simple and you may want to override it. More Nova features like Refund Action or Cards are coming.

## Tests

TODO

## Contribute

This package is still in development, feel free to contribute!

### Contributors

- [MarceauKa](https://github.com/MarceauKa)
- and all others [contributors](https://github.com/404labfr/laravel-impersonate/graphs/contributors)

## Licence

MIT
