<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return 'api horarios 1.0';
});

Route::group(['middleware' => ['cors']], function(){
    // disabled not used routes.
    Auth::routes(['register' => false, 'reset' => false]);
    Route::options('/oauth/{any?}', function(){
        return response()->json([]);
    });
});

