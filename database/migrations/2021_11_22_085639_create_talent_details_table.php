<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTalentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('talent_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title')->nullable();
            $table->text('about')->nullable();
            $table->string('avatar')->nullable();
            $table->string('social_instagram')->nullable();
            $table->string('social_snapchat')->nullable();
            $table->string('social_youtube')->nullable();
            $table->string('social_twitter')->nullable();
            $table->string('social_tik_tok')->nullable();
            $table->integer('status')->nullable();
            $table->tinyInteger('is_featured')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_owner')->nullable();
            $table->string('bank_iban')->nullable();
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
        Schema::dropIfExists('talent_details');
    }
}
