<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UrlEntries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('url_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('url_id');
            $table->string('ip');
            $table->timestamps();

            $table
                ->foreign('url_id')
                ->references('id')
                ->on('urls');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('url_entries');
    }
}
