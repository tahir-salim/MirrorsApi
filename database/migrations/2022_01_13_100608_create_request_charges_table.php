<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_request_id');
            $table->foreignId('transaction_id')->nullable();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->double('price');
            $table->boolean('paid_with_request')->default(0);
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
        Schema::dropIfExists('request_charges');
    }
}
