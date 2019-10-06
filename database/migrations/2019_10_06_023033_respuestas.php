<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Respuestas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citas_respuestas', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('cita_id');
            $table->string('idx')->unique();
            $table->json('data')->nullable();
            $table->timestamps();
            $table->foreign('cita_id')->references('id')->on('citas');
            $table->index('idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('citas_respuestas');
    }
}
