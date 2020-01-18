<?php

namespace App\Http\Controllers;

use App\Historia;
use Illuminate\Http\Request;

class HistoriaController extends Controller
{
    function index(Request $request) {
        $historias = Historia::orderBy('created_at')
                    ->with(['cliente']);
        if($request->all()) {
            foreach($request->all() as $column => $value) {
                if($column != 'page') {
                    if(is_numeric($value))
                    {
                        $historias->where($column, $value);
                    } else {
                        $historias->where($column, 'ilike', $value . '%');
                    }
                }
            }
        }
        $historias = $historias->paginate(15);
        return response()->json($historias);
    }

    function store(Request $request) {
        $historia = new Historia();
        $historia->fill($request->all());
        $historia->save();
        return response()->json(['id' => $historia->id]);
    }

    function update(Request $request, $id) {
        $historia = Historia::find($id);
        $historia->fill($request->all());
        $historia->save();
        return response()->json('ok');
    }

    function show($id) {
        $historia = Historia::with(['cliente'])->whereId($id)->first();
        return response()->json($historia);
    }
}
