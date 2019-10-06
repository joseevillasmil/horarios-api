<?php

namespace App\Http\Controllers;

use App\Cita;
use App\Contenedor;
use Auth;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    function store(Request $request){
        $cita = new Cita();
        $cita->fill($request->all());
        $contenedor = Contenedor::select('id')
                        ->where('idx', $request->contenedor)
                        ->first();
        $cita->contenedor_id = $contenedor->id;
        $cita->usuario_id = Auth::user()->id;
        $cita->idx = uniqid(dechex(rand(100000,999999)), true);
        $cita->save();

        return response()->json(['idx' => $cita->idx]);
    }

    function index(Request $request){
        $columns = ['idx', 'nombre'];
        $citas = Cita::select($columns)
            ->orderBy('nombre')
            ->get();
        return response()->json($citas);
    }

    function show(Request $request, $idx){
        $columns = ['idx', 'usuario_id', 'contenedor_id', 'estado', 'comentario', 'inicio', 'fin'];
        $cita = Cita::select($columns)
            ->where('idx', $idx)
            ->first();
        $cita->load(['contenedor', 'usuario', 'respuestas', 'archivos']);
        return response()->json($cita);
    }

    function update(Request $request, $idx){
        $cita = Cita::where('idx', $idx)
            ->first();
        $cita->fill($request->all());
        $cita->save();

        return response()->json(['idx' => $cita->idx]);
    }

    function destroy($idx){
        $cita = Cita::where('idx', $idx)
            ->first();
        $cita->delete();

        return response()->json(['ok']);
    }

    function avaiableDate(Request $request){
        $contenedor = Contenedor::select('idx','configuracion')
                        ->where('idx', $request->contenedor)
                        ->first();
        $avaiable = [];
        $c = cal_days_in_month(CAL_GREGORIAN, $request->mes, date('Y'));
        $today = \DateTime::createFromFormat('Y-m-d', date('Y-m-d'));

        for($i = 1; $i <= $c; $i++)
        {
            $date_str = date('Y')."-$request->mes-".str_pad($i, 2, '0', STR_PAD_LEFT);
            $date = \DateTime::createFromFormat('Y-m-d', $date_str);

            if($date >= $today )
            {
                if($contenedor and $contenedor->configuracion and isset($contenedor->configuracion['weekdays']))
                {
                    $dayoftheweek = strtolower(date('l', strtotime( $date->format('Y-m-d')) ) );

                    if(isset($contenedor->configuracion['weekdays'][$dayoftheweek]) and $contenedor->configuracion['weekdays'][$dayoftheweek]['avaiable']
                        or (
                                isset($contenedor->configuracion['special_days'])
                                and in_array($date->format('Y-m-d'), $contenedor->configuracion['special_days'])
                            )
                        )
                    {

                        if(isset($contenedor->configuracion['holidays']) and !in_array($date->format('Y-m-d'), $contenedor->configuracion['holidays'])){
                            $avaiable[] = $date->format('Y-m-d');
                        }
                    }
                }
            }
        }

        return response()->json($avaiable);
    }

    function avaiableTime(Request $request){
        $contenedor = Contenedor::select('idx','config')
                        ->where('contenedor', $request->contenedor)
                        ->first();

    }
}
