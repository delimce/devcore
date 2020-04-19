<?php

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

$router->get('/', function () {
    return redirect()->route('landingManager');
});

$router->get('/manager', ['as' => 'landingManager', 'uses' => 'Web\LandingController@managerHome']);
$router->get('/manager/signUp', ['as' => 'signUp', 'uses' => 'Web\LandingController@managerSignUp']);

