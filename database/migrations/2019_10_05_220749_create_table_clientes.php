<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableClientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idx');
            $table->string('nombre');
            $table->string('correo');
            $table->string('dni');
            $table->string('telefono');
            $table->string('sexo');
            $table->date('nacimiento');
            $table->string('pais');
            $table->integer('peso')->nullable()->comment('en kg');
            $table->integer('estatura')->nullable()->comment('en cm');
            $table->double('imc')->nullable();
            $table->string('codigo');
            $table->timestamp('verificado')->nullable();
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
        Schema::dropIfExists('clientes');
    }
}
