<?php

use App\Models\RequestCharges;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentStatusToRequestChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_charges', function (Blueprint $table) {
            $table->integer('payment_status')->after('transaction_id')->default(1);
        });

        RequestCharges::whereHas('transaction', function($q) {
            $q->where('tap_status', 'CAPTURED');
        })->update(['payment_status' => RequestCharges::STATUS_PAID]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_charges', function (Blueprint $table) {
            //
        });
    }
}
