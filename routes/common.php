<?php
/** @var \Laravel\Lumen\Routing\Router $router */

$router->group(
    ['prefix' => '/settings'],
    function () use ($router) {
        $router->get('/', 'AppSettingsController@index');
        $router->post('/', 'AppSettingsController@update');
        $router->delete('/{name}', 'AppSettingsController@delete');
    }
);

/*
$router->group(
    ['prefix' => '/category'],
    function () use ($router) {
        $router->get('/', 'ProductCategoryController@index');
        $router->get('/{id}', 'ProductCategoryController@find');

        //manager or higher can do bellow actions
        $router->group(
            ['middleware' => 'role:manager'],
            function () use ($router) {
                $router->post('/', 'ProductCategoryController@store');
                $router->post('/{id}', 'ProductCategoryController@update');
                $router->delete('/{id}', 'ProductCategoryController@delete');
            }
        );
    }
);
*/