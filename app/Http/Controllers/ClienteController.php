<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Mail\ValidarEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use DB;
class ClienteController extends Controller
{
    function index(Request $request) {
        if ( $request->list ) {
            $clientes = Cliente::select('idx', DB::raw("concat(nombre, ' ', dni) as text"))
                        ->orderBy('nombre')
                        ->get();
            $return = [];
            foreach($clientes as $cliente) {
                $return[] = ['id' => $cliente->idx, 'text' => $cliente->text];
            }
            return response()->json(['data' => $return]);
        } else {
            $clientes = Cliente::orderBy('nombre');
        }

        if($request->all()) {
            foreach($request->all() as $column => $value) {
                if($column != 'page') {
                    $clientes->where($column, 'like', $value . '%');
                }
            }
        }

        if($request->all) {
            return response()->json(['data' => $clientes]);
        }

        $clientes = $clientes->paginate(15);
        return response()->json($clientes);

    }

    function store(Request $request) {
        if(Cliente::where('dni', 'like', $request->dni)->count()) {
            return response()->json(['error' => 'existe']);
        }
        $cliente = new Cliente();
        $cliente->fill($request->all());
        $cliente->codigo = Str::random(8);
        if ($request->activate) {
            $cliente->verificado = date('Y-m-d H:i');
        }
        $cliente->save();
        /*Mail::to($request->correo)
            ->queue(new ValidarEmail($cliente->codigo));
        */
        return response()->json(['idx' => $cliente->idx, 'error' => null]);
    }
    function update(Request $request, $id) {
        if(Cliente::where('dni', 'like', $request->dni)
                    ->where('id', '!=', $id)
                    ->count() ) {
            return response()->json(['error' => 'existe']);
        }
        $cliente = Cliente::find($id);
        if(!$cliente) {
            return response()->json(['error' => 'no existe']);
        }
        $cliente->fill($request->all());
        $cliente->codigo = Str::random(8);
        if ($request->activate) {
            $cliente->verificado = date('Y-m-d H:i');
        }
        $cliente->save();
        /*Mail::to($request->correo)
            ->queue(new ValidarEmail($cliente->codigo));
        */
        return response()->json(['idx' => $cliente->idx, 'error' => null]);
    }

    function show($id) {
        $client = Cliente::find($id);
        $client->load('citas');
        if (!$client) {
            return response()->json(['error' => 'No existe'], 404);
        }
        $client->nacimiento = $client->nacimiento ? $client->nacimiento->format('Y-m-d') : null;
        return response()->json($client);
    }

    function find(Request $request) {
        $client = Cliente::select('idx', 'nombre', 'dni', 'correo', 'telefono')
                        ->where('correo', 'like', $request->email)->first();
        if (!$client) {
            return response()->json(['error' => 'No existe'], 404);
        }
        return response()->json($client);
    }

    function validar(Request $request) {
        $cliente = Cliente::where('idx', $request->cliente)
                            ->where('codigo', $request->codigo)
                            ->whereNull('verificado')
                            ->first();
        if($cliente) {
            $cliente->verificado = date('Y-m-d H:i:s');
            $cliente->save();
            return response()->json('ok');
        }
        return response()->json('fail');
    }

    function findByEmail(Request $request) {
        $cliente = Cliente::where('correo', 'like', $request->correo)
                            ->whereNotNull('verificado')
                            ->first();
        return response()->json($cliente);
    }
}
