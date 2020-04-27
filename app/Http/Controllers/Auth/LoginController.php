<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    function login(Request $request) {
        if (Auth::attempt($request->only('email', 'password'))) {
            $token = Auth::user()->createToken('', ['*']);
            return response()->json([
                'access_token' => $token->accessToken,
                'expires_in' => strtotime($token->token->expires_at->format('Y-m-d H:i:s')) * 1000,
            ]);
        } else {
            return response()->json('error',404);
        }
    }
}
