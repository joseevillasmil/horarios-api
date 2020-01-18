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
            $table->bigInteger('cliente_id');
            $table->integer('contenedor_id');
            $table->string('idx')->unique();
            $table->string('estado')->default('pendiente');
            $table->longText('comentario')->nullable();
            $table->timestamp('inicio');
            $table->timestamp('fin');
            $table->string('codigo');
            $table->timestamp('verificada')->nullable();
            $table->timestamps();
            $table->foreign('contenedor_id')->references('id')->on('contenedores');
            $table->foreign('cliente_id')->references('id')->on('clientes');
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
