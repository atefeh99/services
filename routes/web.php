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
$router->group(['prefix' => '/api/v0'], function () use ($router) {

    $router->post("/{input}/{output}", "GnafController@search");

});
$router->group(['prefix' => '/routes'], function () use ($router) {
    $router->get("/{id}", "RouteCRUDController@readItem");
    $router->post("", "RouteCRUDController@createItem");
    $router->get("", "RouteCRUDController@showAll");
    $router->patch("/{id}", "RouteCRUDController@updateField");
    $router->delete("/{id}", "RouteCRUDController@deleteRecord");


});

