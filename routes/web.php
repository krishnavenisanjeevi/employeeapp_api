<?php

$router->get('/', function () use ($router) {
    return response()->json($router->app->version());
});

        $router->group(
            ['prefix' => 'employee'],
            function ($router) {
                $router->get('/all', 'EmployeeController@index');
                $router->post('create', 'EmployeeController@store');
                $router->get('/{id}', 'EmployeeController@show');

            }
        );




