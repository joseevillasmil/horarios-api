<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $fillable = ['nombre', 'correo', 'dni', 'telefono', 'sexo', 'nacimiento', 'pais'];
    protected $dates = ['created_at', 'updated_at', 'nacimiento'];

    function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->idx = uniqid(Str::random(10));
    }

    function citas() {
        return $this->hasMany('App\Cita', 'cliente_id')->orderBy('id', 'desc');
    }

    function historias() {
        return $this->hasMany('App\Historia', 'cliente_id');
    }

}
