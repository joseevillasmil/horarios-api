<?php

namespace App\Http\Controllers;

use App\Comentario;
use Illuminate\Http\Request;

class ComentarioController extends Controller
{
    function store(Request $request) {
        $comentario = new Comentario();
        $comentario->texto = $request->texto;
        $comentario->cita_id = $request->cita_id;
        $comentario->save();
        return response()->json('ok');
    }

    function  index(Request $request) {
        $comentarios = Comentario::whereCitaId($request->cita_id)
                            ->orderBy('id', 'desc')
                            ->get();
        return response()->json($comentarios);
    }
}
