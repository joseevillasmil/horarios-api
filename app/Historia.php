<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Historia extends Model
{
    protected $table = 'historias';
    protected $fillable = ['contenido', 'conclucion', 'cliente_id', 'estado'];

    function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->autor_id = Auth::user()->id;
    }

    function cliente() {
        return $this->belongsTo('App\Cliente', 'cliente_id');
    }

    function archivos(){
        $this->hasMany('Archivo', 'relation_id')->where('relation', 'historia');
    }
}
