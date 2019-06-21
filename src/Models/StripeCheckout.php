<?php

namespace Lab404\StripeServer\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StripeCheckout extends Model
{
    /** @var string $table */
    protected $table = 'stripe_checkouts';
    /** @var array $fillable */
    protected $fillable = [
        'payment_intent_id',
        'checkout_session_id',
        'is_paid',
        'chargeable_type',
        'chargeable_id',
    ];
    /** @var array $casts */
    protected $casts = [
        'is_paid' => 'bool',
    ];

    public function chargeable(): MorphTo
    {
        return $this->morphTo();
    }

    public function markAsPaid(): bool
    {
        $this->attributes['is_paid'] = 1;
        return $this->save();
    }

    public function scopeIsPaid(Builder $query): void
    {
        $query->where('is_paid', 1);
    }

    public function scopeIsNotPaid(Builder $query): void
    {
        $query->where('is_paid', 0);
    }

    public function scopePaymentIntentIdsAre(Builder $query, array $ids): Builder
    {
        return $query->whereIn('payment_intent_id', $ids);
    }

    public function scopePaymentIntentIdIs(Builder $query, string $payment_intent_id): Builder
    {
        return $query->where('payment_intent_id', '=', $payment_intent_id);
    }

    public function scopeCheckoutSessionIdIs(Builder $query, string $checkout_session_id): Builder
    {
        return $query->where('checkout_session_id', '=', $checkout_session_id);
    }

    public function scopeCheckoutSessionIdsAre(Builder $query, array $ids): Builder
    {
        return $query->whereIn('checkout_session_id', $ids);
    }
}
