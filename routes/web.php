<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/cuaca', 'CuacaController@apiCuaca');
$router->get('/cuaca-kec', 'CuacaController@apiCuacaByIdKec');
$router->get('/cuaca-kec/{id}', 'CuacaController@apiCuacaByIdKec');
$router->get('/cuaca-kec-detail', 'CuacaController@apiCuacaDetailByIdKec');
$router->get('/cuaca-kec-detail/{id}', 'CuacaController@apiCuacaDetailByIdKec');
$router->get('/cuaca-coor', 'CuacaController@index');
$router->get('/lokasi', 'LokasiController@getLokasi');
$router->get('/gempa', 'GempaController@getGempa');
$router->get('/gempa-terbaru', 'GempaController@getLatestGempa');
