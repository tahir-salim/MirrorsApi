<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateIsFeaturedFieldInTalentPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('talent_posts', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->change();
            $table->longText('body')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('talent_posts', function (Blueprint $table) {
            $table->integer('is_featured')->change();
            $table->string('body')->nullable()->change();
        });
    }
}
