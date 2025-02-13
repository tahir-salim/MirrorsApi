<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameRequestIdColumnsToBookingRequestId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function(Blueprint $table)
        {
            $table->renameColumn('request_id', 'booking_request_id');
        });

        Schema::table('reviews', function(Blueprint $table)
        {
            $table->renameColumn('request_id', 'booking_request_id');
        });

        Schema::table('request_services', function(Blueprint $table)
        {
            $table->renameColumn('request_id', 'booking_request_id');
        });

        Schema::table('request_packages', function(Blueprint $table)
        {
            $table->renameColumn('request_id', 'booking_request_id');
        });

        Schema::table('request_comments', function(Blueprint $table)
        {
            $table->renameColumn('request_id', 'booking_request_id');
        });

        Schema::table('request_attachments', function(Blueprint $table)
        {
            $table->renameColumn('request_id', 'booking_request_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
