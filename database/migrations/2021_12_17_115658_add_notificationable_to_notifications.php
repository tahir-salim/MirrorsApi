<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotificationableToNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('notificationable_type')->nullable();
            DB::statement('ALTER TABLE notifications CHANGE action_id action_id INTEGER  DEFAULT NULL;');
            DB::statement('ALTER TABLE notifications CHANGE is_read is_read BOOLEAN  DEFAULT 0;');
            $table->renameColumn('action_id', 'notificationable_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            //
        });
    }
}
