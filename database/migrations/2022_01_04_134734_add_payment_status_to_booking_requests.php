<?php

use App\Models\BookingRequest;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentStatusToBookingRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking_requests', function (Blueprint $table) {
            $table->integer('payment_status')->after('transaction_id')->nullable();
        });

       BookingRequest::whereHas('transaction', function($q) {
            $q->where('tap_status', 'CAPTURED');
        })->update(['payment_status' => BookingRequest::STATUS_PAID]);


        BookingRequest::whereNull('transaction_id')->orWhereHas('transaction', function($q) {
            $q->whereNotIn('tap_status', ['CAPTURED']);
        })->update(['payment_status' => BookingRequest::STATUS_UNPAID]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_requests', function (Blueprint $table) {
            //
        });
    }
}
