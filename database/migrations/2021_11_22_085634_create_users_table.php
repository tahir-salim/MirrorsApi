<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('country_id')->nullable();
            $table->string('phone');
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('password');
            $table->integer('role_id');
            $table->tinyInteger('is_blocked');
            $table->string('device_os')->nullable();
            $table->string('device_os_version')->nullable();
            $table->string('device_token')->nullable();
            $table->string('device_name')->nullable();
            $table->string('app_version')->nullable();
            $table->string('last_ip_address')->nullable();
            $table->timestamp('last_activity')->nullable();
            $table->tinyInteger('is_social');
            $table->string('provider_id')->nullable();
            $table->string('google_id')->nullable();
            $table->string('provider')->nullable();
            $table->string('provider_token')->nullable();
            $table->string('branch_origin')->nullable();
            $table->string('branch_origin_id')->nullable();
            $table->string('remember_token')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('users');
    }
}
