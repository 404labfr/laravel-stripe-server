<?php

namespace Lab404\StripeServer\Nova;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Lab404\StripeServer\Models\StripeCheckout as Model;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class StripeCheckout extends Resource
{
    /** @var string $model */
    public static $model = Model::class;
    /** @var array $with */
    public static $with = ['chargeable'];

    public function fields(Request $request): array
    {
        return [
            ID::make('ID')
                ->hideFromIndex()
                ->asBigInt(),

            Boolean::make(__('Paid'), 'is_paid'),

            Text::make(__('Session ID'), 'checkout_session_id')
                ->exceptOnForms(),

            Text::make(__('Payment intent'), 'payment_intent_id')
                ->exceptOnForms(),

            Text::make(__('Chargeable type'), 'chargeable_type')
                ->exceptOnForms(),

            Text::make(__('Chargeable ID'), 'chargeable_id')
                ->exceptOnForms(),

            DateTime::make(__('Created at'), 'created_at')
                ->exceptOnForms(),

            DateTime::make(__('Updated at'), 'updated_at')
                ->exceptOnForms(),
        ];
    }

    public function cards(Request $request): array
    {
        return [];
    }

    public function filters(Request $request): array
    {
        return [];
    }

    public function lenses(Request $request): array
    {
        return [];
    }

    public function actions(Request $request): array
    {
        return [];
    }

    public static function singularLabel(): string
    {
        return __('Checkout');
    }

    public static function label(): string
    {
        return __('Checkouts');
    }
}
