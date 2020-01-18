<?php

namespace App\Http\Controllers;

use App\Cita;
use App\Contenedor;
use App\Cliente;
use App\Mail\Confirm;
use App\Mail\resetPassword;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use mysql_xdevapi\Collection;

class CitaController extends Controller
{
    function store(Request $request){
        $cliente = Cliente::where('idx', $request->cliente)->first();
        $cita = new Cita();
        $cita->fill($request->all());
        $contenedor = Contenedor::select('id')
                        ->where('idx', $request->contenedor)
                        ->first();
        $cita->cliente_id = $cliente->id;
        $cita->contenedor_id = $contenedor->id;
        $cita->codigo = Str::random(6);
        $cita->idx = uniqid(dechex(rand(100000,999999)), true);
        $cita->save();
        Mail::to($cliente->correo)->send(new Confirm($cita->inicio));
        return response()->json(['idx' => $cita->idx]);
    }

    function validar(Request $request) {
        $cita = Cita::where('idx', $request->idx)
                    ->where('codigo', $request->codigo)
                    ->whereNull('validada')
                    ->first();
        if ($cita) {
            $cita->validada = date('Y-m-d H:i:s');
            $cita->save();
            return response()->json('ok');
        }
        return response()->json('fail');
    }

    function index(Request $request){
        $columns = [ 'idx', 'inicio', 'fin', 'cliente_id', 'contenedor_id',
                    'estado', 'codigo', 'comentario', 'verificada' ];
        $citas = Cita::select($columns)
                        ->with(['contenedor', 'cliente']);
        if($request->month) {
            $citas->whereMonth('inicio', $request->month);
        }
        if($request->day) {
            $citas->whereDate('inicio', $request->day);
        }
        if($request->cantainer) {
            $citas->whereHas('container', function($q) use($request){
                $q->where('idx', $request->container);
            });
        }
        $citas = $citas->orderBy('inicio')
        ->get();
        return response()->json($citas);
    }

    function show(Request $request, $idx){
        $columns = ['idx', 'usuario_id', 'contenedor_id', 'estado', 'comentario', 'inicio', 'fin'];
        $cita = Cita::select($columns)
            ->where('idx', $idx)
            ->with(['contenedor', 'cliente', 'archivos', 'historias'])
            ->first();
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

        // Buscamos la cantidad de días del mes y la fecha actual.
        $avaiable = [];
        if($request->mes) {
            $mes = $request->mes;
            $maximo = (int)$request->mes;
        }else {
            $maximo = 12;
            $mes = date('m');
        }

        $today = \DateTime::createFromFormat('Y-m-d', date('Y-m-d'));

        //Recorremos.
        for($j = (int)$mes; $j <= $maximo; $j++) {
            $c = cal_days_in_month(CAL_GREGORIAN, str_pad($j, 2, '0', STR_PAD_LEFT), date('Y'));
            for ($i = 1; $i <= $c; $i++) {
                $date_str = date('Y') . "-".str_pad($j, 2, '0', STR_PAD_LEFT)."-" . str_pad($i, 2, '0', STR_PAD_LEFT);

                $date = \DateTime::createFromFormat('Y-m-d', $date_str);
                print_r($date);
                if ($date >= $today) {
                    if ($contenedor and $contenedor->configuracion and $contenedor->configuracion->weekdays) {
                        $dayoftheweek = strtolower(date('l', strtotime($date->format('Y-m-d'))));
                        echo $dayoftheweek ."\n";
                        if ($contenedor->configuracion->weekdays->$dayoftheweek and $contenedor->configuracion->weekdays->$dayoftheweek->avaiable
                            or (
                                property_exists($contenedor->configuracion, 'extra_days')
                                and $contenedor->configuracion->extra_days
                                and in_array($date->format('Y-m-d'), $contenedor->configuracion->extra_days)
                            )
                        ) {

                            if ($contenedor->configuracion->holidays and !in_array($date->format('Y-m-d'), $contenedor->configuracion->holidays)) {
                                //Si llegamos hasta aca, buscamos que los slots usados no superen el limite.
                                $citas = Cita::whereDate('inicio', $date->format('Y-m-d'))
                                    ->count();
                                $time = $contenedor->configuracion->weekdays->$dayoftheweek->time;
                                $start = $this->timeToMinutes($time[0]);
                                $end = $this->timeToMinutes($time[1]);

                                $c_horas = ($end - $start) * $contenedor->configuracion->slots / $contenedor->configuracion->step;
                                if ($citas < $c_horas) {
                                    $avaiable[] = $date->format('Y-m-d');
                                }
                            }
                        }
                    }
                }
            }
        }
        die;
        return response()->json($avaiable);
    }

    function avaiableTime(Request $request){
        $contenedor = Contenedor::select('idx','configuracion')
                        ->where('idx', $request->contenedor)
                        ->first();
        $date = \DateTime::createFromFormat('Y-m-d', $request->day);
        $dayoftheweek = strtolower(date('l', strtotime( $date->format('Y-m-d')) ) );
        $time = $contenedor->configuracion->weekdays->$dayoftheweek->time;
        $aux = $time[0];
        $steps = [];
        do{
            $steps[] = $aux;
            $endTime = strtotime("+" . $contenedor->configuracion->step . " minutes", strtotime($aux));
            $aux = date('H:i', $endTime);
        }while(strtotime($aux) < strtotime($time[1]) && count($steps) < 1440);

        $taken = Cita::select(\DB::raw('to_char(inicio, \'HH24:MI\') as time'))
                    ->whereDate('inicio', $request->day)
                    ->groupBy('time')
                    ->havingRaw('count(id) >='. $contenedor->configuracion->slots)
                    ->pluck('time')
                    ->toArray();
;
        $return = [];
        foreach($steps as $step) {
            if(!in_array($step, $taken) ) {
                $return[] = $step;
            }
        }

        return response()->json($return);
    }

    function timeToMinutes($time) {
        $arr = explode(':', $time);
        if(count($arr) >= 2 ) {
            return (int)$arr[0]*60 + (int)$arr[1];
        } else {
            return 0;
        }
    }
}
