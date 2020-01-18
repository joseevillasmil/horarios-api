<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->idx = uniqid(Str::random(10), true);
    }

    function contenedor(){
        return $this->belongsTo('App\Contenedor', 'contenedor_id');
    }

    function cliente(){
        return $this->belongsTo('App\Cliente', 'cliente_id');
    }

    function archivos(){
        return $this->hasMany('App\Archivo', 'relation_id')->where('relation', 'cita');
    }

}
