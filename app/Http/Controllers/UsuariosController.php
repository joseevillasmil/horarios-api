<?php

namespace App\Http\Controllers;

use App\Comentario;
use App\Mail\resetPassword;
use App\Mail\resetPasswordRequest;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UsuariosController extends Controller
{
    function store(Request $request){
        $usuario = new User();
        $usuario->fill($request->all());
        $usuario->save();
        return response()->json(['id' => $usuario->id]);
    }

    function show($id){
        $usuario = User::select('name', 'email', 'id')
                            ->where('id', $id)
                            ->first();
        return response()->json($usuario);
    }

    function passwordResetRequest(Request $request) {
        $usuario = User::where('email', 'like', $request->email)->first();
        if(!$usuario) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $token = uniqid(Str::random(10), true);
        DB::table('users_password_reset')->insert(
            [
                'user_id' => $usuario->id,
                'token' => $token
            ]
        );

        Mail::to($usuario->email)->send(new resetPasswordRequest($token));
        return response()->json(['ok'], 200);
    }

    function passwordReset(Request $request) {
        $token = DB::table('users_password_reset')->where('token', $request->token)
                    ->whereNull('used')
                    ->first();
        if(!$token) {
            return response()->json(['error' => 'Invalid Token'], 404);
        }
        $usuario = User::find($token->user_id);
        if(!$usuario) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $usuario->password = bcrypt($request->password);
        $usuario->save();
        DB::table('users_password_reset')
                ->whereId($token->id)
                ->update(['used' => date('Y-m-d H:i:s')]);
        Mail::to($usuario->email)->send(new resetPassword());
        return response()->json(['ok'], 200);
    }

}
