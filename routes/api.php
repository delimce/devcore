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
            $router->put('reset', ['uses' =>  'ManagerController@resetSendMessage']);
            $router->put('reset/password', ['uses' =>  'ManagerController@resetPassword']);

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

                    $router->group(['prefix' => 'schedule'], function () use ($router) {
                        $router->get('/', ['uses' =>  'GarageController@getSchedule']);
                        $router->post('/', ['uses' =>  'GarageController@saveSchedule']);
                    });

                    $router->group(['prefix' => 'media'], function () use ($router) {
                        $router->post('/', ['uses' =>  'GarageController@saveMedia']);
                        $router->get('/{garageId}', ['uses' =>  'GarageController@getMedia']);
                        $router->delete('/', ['uses' =>  'GarageController@removeMedia']);
                    });

                    $router->group(['prefix' => 'services'], function () use ($router) {
                        $router->post('/', ['uses' =>  'GarageController@saveService']);
                        $router->delete('/', ['uses' =>  'GarageController@removeService']);
                        $router->get('/{garageId}/list', ['uses' =>  'GarageController@getServices']);
                        $router->get('/id/{serviceId}', ['uses' =>  'GarageController@getServiceById']);
                        $router->get('/segments', ['uses' =>  'GarageController@getSegments']);
                        $router->get('/types', ['uses' =>  'GarageController@getServicesTypes']);
                        $router->get('/categories', ['uses' =>  'GarageController@getServicesCategories']);
                        $router->get('/catalog', ['uses' =>  'GarageController@getServiceCatalog']);
                        $router->get('/brands', ['uses' =>  'GarageController@getServiceBrands']);
                    });
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
