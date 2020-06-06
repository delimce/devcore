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
            $router->post('login', ['uses' =>  'ManagerController@doLogin']);

            //auth admin
            $router->group(
                ['middleware' => ['api'], 'prefix' => 'auth'],
                function () use ($router) {
                    $router->get('/', ['uses' =>  'ManagerController@main']);
                    $router->put('/info/save', ['uses' =>  'ManagerController@saveMain']);
                    $router->put('/password', ['uses' =>  'ManagerController@changePassword']);
                    $router->put('/company/save', ['uses' =>  'ManagerController@saveCompany']);
                }
            );

            ///garage
            $router->group(
                ['middleware' => ['api'], 'prefix' => 'garage'],
                function () use ($router) {
                    $router->post('/', ['uses' =>  'GarageController@saveGarage']);
                    $router->get('/info', ['uses' =>  'GarageController@getGarageInfo']);
                    $router->get('/networks', ['uses' =>  'GarageController@getNetworks']);
                    $router->get('/schedule', ['uses' =>  'GarageController@getSchedule']);
                    $router->post('/schedule', ['uses' =>  'GarageController@saveSchedule']);
                    $router->post('/media', ['uses' =>  'GarageController@saveMedia']);
                    $router->get('/media/{garageId}', ['uses' =>  'GarageController@getMedia']);
                    $router->delete('/media', ['uses' =>  'GarageController@removeMedia']);
                }
            );
        });

        //localization
        $router->group(
            ['prefix' => 'local'],
            function () use ($router) {
                $router->get('/states[/{countryId}]', ['uses' =>  'LocalizationController@getStates']);
                $router->get('/provinces[/{stateId}]', ['uses' =>  'LocalizationController@getProvinces']);
                $router->get('/municipalities[/{provinceId}]', ['uses' =>  'LocalizationController@getMunicipalities']);
            }
        );
    }

);
