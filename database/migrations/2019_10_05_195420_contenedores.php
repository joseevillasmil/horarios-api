<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Contenedores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contenedores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idx');//->unique();
            $table->string('nombre');//->index();
            $table->longText('comentario');
            $table->json('configuracion');
            $table->timestamps();
           // $table->index(['idx', 'nombre']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('contenedores');
    }
}
