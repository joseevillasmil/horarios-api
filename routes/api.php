<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ComentarioController;
use App\User;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('{client}')->group(function() {
    Route::middleware('client')->group(function () {
        Route::middleware('auth:api')->get('user', function (Request $request) {
            return $request->user();
        });
        Route::post('login', 'Auth\LoginController@login');
        Route::resource('citas', 'CitaController');
        Route::resource('comentarios', 'ComentarioController');
        Route::resource('archivos', 'ArchivoController');
        Route::get('clients/find', 'ClienteController@find');
        route::get('clientes/{id}/comentarios', 'ClienteController@getCommentarios');
        Route::resource('contenedores', 'ContenedorController');
        Route::get('public/contenedores/{idx}', 'ContenedorController@showByIdx')->name('contenedores.show-by-idx');
        Route::resource('usuarios', 'UsuariosController');
        Route::post('usuarios/reset-password-request', 'UsuariosController@passwordResetRequest')->name('usuarios.reset-password-request');
        Route::post('usuarios/reset-password', 'UsuariosController@passwordReset')->name('usuarios.reset-password');
        Route::resource('historias', 'HistoriaController');
        Route::get('avaiable-dates', 'CitaController@avaiableDate');
        Route::get('avaiable-time', 'CitaController@avaiableTime');
        Route::resource('clientes', 'ClienteController');
        Route::post('clientes/validar/{codigo}', 'ClienteController@validar');
    });
});
