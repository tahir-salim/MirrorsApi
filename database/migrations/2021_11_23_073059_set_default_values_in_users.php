<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetDefaultValuesInUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE users CHANGE is_blocked is_blocked BOOLEAN  DEFAULT 0;');
        DB::statement('ALTER TABLE users CHANGE is_social is_social BOOLEAN  DEFAULT 0;');

        Schema::table('users', function (Blueprint $table) {
            //
            $table->unique('phone');
            $table->unique('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
