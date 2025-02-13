<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('request_id');
            $table->integer('status');
            $table->double('amount');
            $table->string('tap_customer_id')->nullable();
            $table->string('tap_charge_id')->nullable();
            $table->string('tap_status')->nullable();
            $table->text('tap_response')->nullable();
            $table->string('currency');
            $table->string('payment_link')->nullable();
            $table->tinyInteger('is_success');
            $table->timestamp('paid_at')->nullable();
            $table->double('usd_amount')->nullable();
            $table->string('origin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
