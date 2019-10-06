<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Archivos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citas_archivos', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('cita_id');
            $table->string('idx')->unique();
            $table->string('filename');
            $table->string('descripcion');
            $table->string('path');
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
        Schema::drop('citas_archivos');
    }
}
