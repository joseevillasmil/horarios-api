<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUsuariosContenedores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios_contenedores', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('usuario_id');
            $table->bigInteger('contenedor_id');
            $table->foreign('contenedor_id')->references('id')->on('contenedores');
            $table->foreign('usuario_id')->references('id')->on('users');
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
        Schema::dropIfExists('usuarios_contenedores');
    }
}
