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


// landing manager
$router->group(
    ['prefix' => 'manager', 'namespace' => 'Web'],
    function () use ($router) {
        $router->get('/', ['as' => 'landingManager', 'uses' => 'LandingController@managerHome']);
        $router->get('/signUp', ['as' => 'signUp', 'uses' => 'LandingController@managerSignUp']);
        $router->get('/activate/{token}', 'LandingController@managerActivate');
        $router->get('/activated', ['as' => 'activated', 'uses' => 'LandingController@managerActivated']);
    }
);

// admin manager
$router->get('/admin/{any:.*}', ['as' => 'admin', 'uses' => 'Web\ManagerController@index']);

// media
$router->get('/storage/media/{folder}/{file}', ['as' => 'media', 'uses' => 'Web\MediaController@serve']);
