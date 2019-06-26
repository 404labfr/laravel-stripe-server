<?php

namespace Lab404\StripeServer\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Lab404\StripeServer\Models\StripeCheckout;

class Purge extends Command
{
    /** @var string $signature */
    protected $signature = 'stripe:purge {--days=7}';
    /** @var string $description */
    protected $description = 'Purge unpaid checkouts';

    public function handle()
    {
        /** @var StripeCheckout $model */
        $model = Config::get('stripe-server.model');

        DB::table($model->getTable())
            ->where('is_paid', 0)
            ->where('created_at', '<=', Carbon::now()->subDays($this->option('days'))->toDateTimeString())
            ->delete();

        $this->info('Stripe checkouts purged.');
    }
}
