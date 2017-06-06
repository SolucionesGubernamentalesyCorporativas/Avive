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
    return view('welcome');
});


Route::resource('contratos', 'ContratoController');
Route::any('seleccionaMembresia','ContratoController@seleccionaMembresia');
Route::get('membresia','ContratoController@membresia');
Route::get('prueba/{id}','ContratoController@muestraPDF');

Route::get('dia/{fecha}','ContratoController@tresPagos');