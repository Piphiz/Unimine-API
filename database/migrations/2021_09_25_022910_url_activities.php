<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UrlActivities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('urls_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('url_id');
            $table->string('ip');
            $table->string('type');
            $table->timestamps();

            $table
                ->foreign('url_id')
                ->references('id')
                ->on('url')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('urls_activities');
    }
}
