<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $table = "citas";
    protected $fillable = ['inicio', 'fin', 'comentario'];
    protected $dates = [
        'created_at',
        'updated_at',
        'inicio',
        'fin'
    ];

    function contenedor(){
        $this->belongsTo('Contenedor', 'contenedor_id');
    }

    function usuario(){
        $this->belongsTo('Usuario', 'usuario_id');
    }

    function archivos(){
        $this->hasMany('CitaArchivo', 'cita_id');
    }

    function respuesta(){
        $this->hasOne('CitaRespuesta', 'cita_id');
    }
}
