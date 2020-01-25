<?php

/*
|--------------------------------------------------------------------------
| Api Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an Api.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|*/

$router->group(
    ['prefix' => 'api', 'namespace' => 'Api'],
    function () use ($router) {

        $router->group(['prefix' => 'manager'], function () use ($router) {
            $router->get('/', ['uses' =>  'ManagerController@index']);
            $router->get('check/{email}', ['uses' =>  'ManagerController@checkEmail']);
            $router->post('signup', ['uses' =>  'ManagerController@signUp']);
        });
    }
);
