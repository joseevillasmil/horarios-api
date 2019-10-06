<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Contenedor;

class ContenedorController extends Controller
{
    function store(Request $request){
        /*
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|unique:contenedores|max:255'
        ]);

        if($validator->fails()){
            return response()->json('error');
        }
        */
        $contenedor = new Contenedor();
        $contenedor->fill($request->all());
        $contenedor->idx = uniqid(dechex(rand(100000,999999)), true);
        $contenedor->save();

        return response()->json(['idx' => $contenedor->idx]);
    }

    function index(Request $request){
        $columns = ['idx', 'nombre'];
        $contenedores = Contenedor::select($columns)
                        ->orderBy('nombre')
                        ->get();
        return response()->json($contenedores);
    }

    function show(Request $request, $idx){
        $columns = ['idx', 'nombre', 'configuracion', 'comentario'];
        $contenedor = Contenedor::select($columns)
            ->where('idx', $idx)
            ->first();
        return response()->json($contenedor);
    }

    function update(Request $request, $idx){
            $contenedor = Contenedor::where('idx', $idx)
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
