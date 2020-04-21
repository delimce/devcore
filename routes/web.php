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


$router->group(
    ['prefix' => 'manager', 'namespace' => 'Web'],
    function () use ($router) {
        $router->get('/', ['as' => 'landingManager', 'uses' => 'LandingController@managerHome']);
        $router->get('/signUp', ['as' => 'signUp', 'uses' => 'LandingController@managerSignUp']);
        $router->get('/activate/{token}', 'LandingController@managerActivate');
    }
);
