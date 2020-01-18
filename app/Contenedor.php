<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contenedor extends Model
{
    protected $table = "contenedores";
    protected $fillable = ['nombre', 'comentario', 'configuracion'];
    protected $casts = ['configuracion' => 'object'];
    /*
     * Modelo de configuracion
     *
     * {
        "weekdays" : {
         "monday": {"avaiable": true, "time" : ["07:00:00", "18:15:00"] },
         "tuesday":{"avaiable": true, "time" : ["07:00:00", "18:30:00"] },
         "wednesday":{"avaiable": true, "time" : ["07:00:00", "18:00:00"] },
         "thursday":{"avaiable": true, "time" : ["07:00:00", "18.00:00"] },
         "friday":{"avaiable": true, "time" : ["07:00:00", "18:30:00"] },
         "saturday":{"avaiable": false },
         "sunday":{"avaiable": false }
        },
        "holidays" : ["2019-10-15"],
        "extra_days" : ["2019-10-20"],
        "step" : 30,
        "slots" : 3
        }


    {
        "weekdays" : {
         "monday": {"avaiable": true, "time" : ["07:00:00", "18:15:00"] },
         "friday":{"avaiable": true, "time" : ["07:00:00", "18:30:00"] },
        }
        "step" : 15,
        "slots" : 1
        }

     */
    function citas(){
        $this->hasMany('Cita', 'contenedor_id');
    }
}
