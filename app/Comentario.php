<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $table = 'comentarios';

    function cita() {
       return $this->belongsTo('App\Cita', 'cita_id');
    }
}
