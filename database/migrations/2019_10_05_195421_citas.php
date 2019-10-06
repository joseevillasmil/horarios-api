<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Citas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('usuario_id');
            $table->integer('contenedor_id');
            $table->string('idx')->unique();
            $table->string('estado')->default('reservada');
            $table->longText('comentario');
            $table->timestamp('inicio');
            $table->timestamp('fin');
            $table->timestamps();
            $table->foreign('contenedor_id')->references('id')->on('contenedores');
            $table->foreign('usuario_id')->references('id')->on('usuarios');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('citas');
    }
}
