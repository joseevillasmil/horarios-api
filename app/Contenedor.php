<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contenedor extends Model
{
    protected $table = "contenedores";
    protected $fillable = ['nombre', 'comentario', 'configuracion'];
    protected $casts = ['configuracion' => 'array'];
    /*
     * Modelo de configuracion
     * {
     *  {
            "weekdays" : {
             "monday": {"avaiable": true, "time" : [7, 18] },
             "tuesday":{"avaiable": true, "time" : [7, 18] },
             "wednesday":{"avaiable": true, "time" : [7, 18] },
             "Thursday":{"avaiable": true, "time" : [7, 18] },
             "friday":{"avaiable": true, "time" : [7, 18] },
             "saturday":{"avaiable": false },
             "sunday":{"avaiable": false }
            },
            "holidays" : ["2019-10-15"],
            "especial_days" : ["2019-10-20"],
            "step" : 30,
            "slots" : 3
            }
     */
    function citas(){
        $this->hasMany('Cita', 'contenedor_id');
    }
}
