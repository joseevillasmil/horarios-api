<?php

namespace App\Http\Controllers;

use App\Archivo;
use Illuminate\Http\Request;

class ArchivoController extends Controller
{
    function store(Request $request) {

        $path = 'documents/' . uniqid() . $request->filename;
        $archivo = new Archivo();
        $archivo->relation = $request->relation;
        $archivo->filename = $request->filename;
        $archivo->relation_id = $request->relation_id;
        $archivo->path = $path;
        $archivo->save();
        $base64 = explode(';base64,', $request->base64)[1];
        \Storage::disk('local')->put($path, base64_decode($base64));
        return response()->json('ok');
    }

    function show($id) {
        $archivo = Archivo::find($id);
        $data = \Storage::disk('local')->get($archivo->path);
        if(!is_dir(storage_path('tmp'))) mkdir(storage_path('tmp'));
        file_put_contents( storage_path('tmp/' . $archivo->filename), $data);
        return response()->download(storage_path('tmp/' . $archivo->filename))
                        ->deleteFileAfterSend(true);
    }

    function index(Request $request) {
        $archivos = Archivo::where('relation', $request->relation)
                            ->where('relation_id', $request->relation_id)
                            ->orderBy('id', 'desc')
                            ->get();
        return response()->json($archivos);
    }
}
