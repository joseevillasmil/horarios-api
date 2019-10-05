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
            $table->string('usuario_id');
            $table->integer('contenedor_id');
            $table->string('idx')->unique();
            $table->boolean('confirmada')->default('false');
            $table->boolean('ejecutada')->default('false');
            $table->longText('comentario');
            $table->timestamp('inicio');
            $table->timestamp('fin');
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
        //
    }
}
