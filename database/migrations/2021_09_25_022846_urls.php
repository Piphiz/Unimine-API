<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Urls extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('urls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('link')->unique();
            $table->string('hash');
            $table->timestamps();
            $table->softDeletes();

            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('RESTRICT')
                ->onUpdate('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('urls');
    }
}
