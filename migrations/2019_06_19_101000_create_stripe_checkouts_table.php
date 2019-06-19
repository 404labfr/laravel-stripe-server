<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStripeCheckoutsTable extends Migration
{
    public function up()
    {
        Schema::create('stripe_checkouts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('payment_intent_id')->nullable()->index();
            $table->string('checkout_session_id')->nullable()->index();
            $table->boolean('is_paid')->default(0)->index();
            $table->string('chargeable_type');
            $table->integer('chargeable_id');
            $table->timestamps();

            $table->index(['chargeable_type', 'chargeable_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('stripe_checkouts');
    }
}
