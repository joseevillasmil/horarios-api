<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Contenedor;

class ContenedorController extends Controller
{
    function store(Request $request){

        $contenedor = new Contenedor();
        $contenedor->fill($request->all());
        $contenedor->configuracion = $request->configuracion;
        $contenedor->idx = uniqid(dechex(rand(100000,999999)), true);
        $contenedor->save();

        return response()->json(['id' => $contenedor->id]);
    }

    function index(Request $request){
        if ( $request->list ) {
            $contenedores = Contenedor::select('idx', DB::raw('nombre as text'))
                ->orderBy('text')
                ->get();
            $return = [];
            foreach($contenedores as $contenedor) {
                $return[] = ['id' => $contenedor->idx, 'text' => $contenedor->text];
            }
            return response()->json(['containers' => $return]);
        }

        $columns = ['id', 'nombre', 'comentario'];
        $contenedores = Contenedor::select($columns)
                        ->orderBy('nombre')
                        ->get();
        return response()->json(['containers' => $contenedores]);
    }

    function show($id){
        $columns = ['id', 'nombre', 'configuracion', 'comentario'];
        $contenedor = Contenedor::select($columns)
            ->find($id);
        if(!$contenedor) {
            return response()->json(['error' => 'no existe'], 404);
        }
        return response()->json($contenedor);
    }

    function showByIdx($idx){
        $columns = ['id', 'nombre', 'configuracion', 'comentario'];
        $contenedor = Contenedor::select($columns)
            ->where('idx', $idx)
            ->first();
        if(!$contenedor) {
            return response()->json(['error' => 'no existe'], 404);
        }
        return response()->json($contenedor);
    }

    function update(Request $request, $id){
            $contenedor = Contenedor::where('id', $id)
                                    ->first();
            $contenedor->fill($request->all());
            $contenedor->save();

            return response()->json(['idx' => $contenedor->idx]);
    }

    function destroy($idx){
            $contenedor = Contenedor::where('idx', $idx)
                                    ->first();
            $contenedor->delete();

            return response()->json(['ok']);
    }
}
