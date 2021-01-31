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

            $router->group(['prefix' => 'support'], function () use ($router) {
                $router->post('/request', ['uses' =>  'SupportController@saveRequest']);
            });

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
                    $router->get('/segments', ['uses' =>  'GarageController@getSegments']);

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
                        $router->post('/', ['uses' =>  'GarageServiceController@saveService']);
                        $router->delete('/', ['uses' =>  'GarageServiceController@removeService']);
                        $router->get('/{garageId}/list', ['uses' =>  'GarageServiceController@getServices']);
                        $router->get('/id/{serviceId}', ['uses' =>  'GarageServiceController@getServiceById']);
                        $router->get('/types', ['uses' =>  'GarageServiceController@getServicesTypes']);
                        $router->get('/categories', ['uses' =>  'GarageServiceController@getServicesCategories']);
                        $router->get('/catalog', ['uses' =>  'GarageServiceController@getServiceCatalog']);
                        $router->get('/brands', ['uses' =>  'GarageServiceController@getServiceBrands']);
                        $router->get('/pool/{garageId}/{segment}', ['uses' =>  'GarageServiceController@getPoolBySegment']);
                        $router->post('/pool', ['uses' =>  'GarageServiceController@savePoolBySegment']);
                    });
                }
            );
        });

        //website garage routes
        $router->group(
            ['prefix' => 'garage'],
            function () use ($router) {
                $router->get('/search', ['uses' =>  'GarageFrontController@mainSearch']);
                $router->get('/search/services', ['uses' =>  'GarageFrontController@searchServices']);
                $router->get('/details/{id}', ['uses' =>  'GarageFrontController@getById']);
            }
        );

        //localization
        $router->group(
            ['prefix' => 'local'],
            function () use ($router) {
                $router->get('/cities/country[/{countryId}]', ['uses' =>  'LocalizationController@getCitiesByCountryId']);
                $router->get('/states[/{countryId}]', ['uses' =>  'LocalizationController@getStates']);
                $router->get('/provinces[/{stateId}]', ['uses' =>  'LocalizationController@getProvinces']);
                $router->get('/municipalities[/{provinceId}]', ['uses' =>  'LocalizationController@getMunicipalities']);
            }
        );

        //users
        $router->group(
            ['prefix' => 'users'],
            function () use ($router) {
                $router->post('login', ['uses' =>  'UserController@login']);
                $router->post('create', ['uses' =>  'UserController@createNew']);
            }
        );
    }

);
